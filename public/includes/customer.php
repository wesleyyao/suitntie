<?php
    class Customer extends Database {
        public $id;
        public $email;
        public $password;
        public $nick_name;
        public $headImg; 
        public $phone;
        public $sex;
        public $city;
        public $province;
        public $country;
        public $full_name;
        public $age;
        public $is_study_aboard;
        
        public function user_signup($email, $phone, $password, $tempId){
            $is_exist = $this->find_user($email);
            if(!$is_exist){
                return $this->signup($email, $phone, $password, $tempId);
            }
            else{
                return "existed";
            }
        }

        private function find_user($email){
            $query = "SELECT `id`, `email`, `status` FROM customers WHERE email = ? LIMIT 1";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();
            return $result->num_rows > 0 ? true : false;
        }
        
        private function signup($email, $phone, $password, $tempId){
            $new_password = password_hash($password, PASSWORD_BCRYPT);
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`email`, `phone`, `password`, `temporary_link`, `status`) VALUES (?, ?, ?, ?, 'open')";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssss", $email, $phone, $new_password, $tempId);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function signup_by_phone($phone, $current_time, $ip){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`phone`, `status`, `date_time`, `ip`) VALUES (?, 'open', ?, ?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sss", $phone, $current_time, $ip);
                if($sql->execute()){
                    $this->id = $this->conn->insert_id;
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_phone_by_user($phone, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "UPDATE customers set phone = ? WHERE id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("si", $phone, $id);
                if($sql->execute()){
                    //$this->id = $this->conn->insert_id;
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function find_user_by_phone($phone){
            $query = "SELECT id FROM customers WHERE phone = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $phone);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                $data = mysqli_fetch_assoc($result);
                if(empty($data["id"])){
                    return false;
                }
                $this->id = $data["id"];
                return true;
            }
            return false;
        }

        public function find_user_by_email($email){
            $query = "SELECT id FROM customers WHERE email = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                $data = mysqli_fetch_assoc($result);
                if(empty($data["id"])){
                    return "not found";
                }
                $this->id = $data["id"];
                return "found";
            }
            return false;
        }

        public function save_new_email_user($email, $password, $date_time, $ip){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`email`, `password`, `status`, `date_time`, `ip`) VALUES (?, ?, 'open', ?, ?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssss", $email, $password, $date_time, $ip);
                if($sql->execute()){
                    $this->id = $this->conn->insert_id;
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function find_user_id($email, $tempId){
            $query = "SELECT `id` FROM customers WHERE email = ? AND temporary_link = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("ss", $email, $tempId);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            return $data;
        }

        public function finish_signup($id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "UPDATE customers SET temporary_link = '' WHERE id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("i", $id);
                if($sql->execute()){
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_current_user($id){
            $query = "SELECT `id`, `email`, `nick_name`, `phone`, `headImg`, `sex`, `city`, `province`, `country`, `unionid`, `full_name`, `age`, `is_study_aboard` FROM customers WHERE `id` = ? AND `status` = 'open'";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            $this->email = $data["email"];
            $this->nick_name = $data["nick_name"];
            $this->phone = $data["phone"];
            $this->headImg = $data["headImg"];
            $this->sex = $data["sex"];
            $this->city = $data["city"];
            $this->province = $data["province"];
            $this->country = $data["country"];
            $this->unionid = $data["unionid"];
            $this->full_name = $data["full_name"];
            $this->age = $data["age"];
            $this->is_study_aboard = $data["is_study_aboard"];
        }

        public function validate_login($email, $password){
            $query = "SELECT `id`, `password` FROM customers WHERE email = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $email);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            if($data && password_verify($password, $data["password"])){
                return $data["id"];
            }
            return false;
        }

        public function find_user_by_uid($uid){
            $query = "SELECT `id` FROM customers WHERE unionid = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $uid);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            if($data && !empty($data["id"])){
                return $data["id"];
            }
            return false;
        }

        public function find_user_by_openid($openid){
            $query = "SELECT `id` FROM customers WHERE openid = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("s", $openid);
            $sql->execute();
            $result = $sql->get_result();
            $data = mysqli_fetch_assoc($result);
            if($data && !empty($data["id"])){
                return $data["id"];
            }
            return false;
        }

        public function save_wechat_uer($nickname, $sex, $city, $province, $country, $headImg, $unionid, $ip, $current_time){
            $status = "open";
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`nick_name`, `sex`, `city`, `province`, `country`, `headImg`, `unionid`, `status`, `date_time`, `ip`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sissssssss", $nickname, $sex, $city, $province, $country, $headImg, $unionid, $status, $current_time, $ip);
                if($sql->execute()){
                    $new_id = $this->conn->insert_id;
                    $this->conn->commit();
                    return $new_id;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_wechat_mobile_user($nickname, $sex, $city, $province, $country, $headImg, $openid, $ip, $current_time){
            $status = "open";
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`nick_name`, `sex`, `city`, `province`, `country`, `headImg`, `openid`, `status`, `date_time`, `ip`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sissssssss", $nickname, $sex, $city, $province, $country, $headImg, $openid, $status, $current_time, $ip);
                if($sql->execute()){
                    $new_id = $this->conn->insert_id;
                    $this->conn->commit();
                    return $new_id;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_user($nickname, $sex, $city, $province, $country, $user_id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "UPDATE customers SET nick_name = ?, sex = ?, city = ?, province = ?, country = ? WHERE id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sisssi", $nickname, $sex, $city, $province, $country, $user_id);
                if($sql->execute()){
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function save_contact($email, $phone, $user_id){
            $this->fetch_current_user($user_id);
            $new_email = empty($email) ? $this->email : $email;
            $new_phone = empty($phone) ? $this->phone : $phone;
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "UPDATE customers SET email = ?, phone = ? WHERE id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssi", $new_email, $new_phone, $user_id);
                if($sql->execute()){
                    $this->conn->commit();
                    return true;
                }
                else{
                    $this->conn->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_all_users(){
            $data = array();
            $query = "SELECT c.id, c.email, c.phone, c.nick_name, c.sex, c.city, c.province, c.country, c.headImg, c.unionId, c.temporary_link, c.status, c.date_time, c.ip, c.full_name, c.age, c.is_study_aboard, COUNT(r.id) as 'results' FROM customers c LEFT JOIN test_results r ON r.user_id = c.id GROUP BY c.id";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }

        public function transfer_user_data_by_phone($phone, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query1 = "UPDATE test_results SET `user_id` = ? WHERE `user_id` = ?";
                $sql = $this->conn->prepare($query1);
                $sql->bind_param("ii", $id, $this->id);
                if(!$sql->execute()){
                    $this->conn->rollback();
                    return false;
                }
                $query2 = "UPDATE customers SET phone = ? WHERE id = ?";
                $sql = $this->conn->prepare($query2);
                $sql->bind_param("si", $phone, $id);
                if(!$sql->execute()){
                    $this->conn->rollback();
                    return false;
                }
                $query3 = "UPDATE customers set `status` = 'close' where id = ?";
                $sql = $this->conn->prepare($query3);
                $sql->bind_param("i", $this->id);
                if(!$sql->execute()){
                    $this->conn->rollback();
                    return false;
                }
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }
    }
?>