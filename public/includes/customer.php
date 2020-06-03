<?php
    class Customer extends Database {
        public $email;
        public $password;
        public $nick_name;
        public $headImg; 
        public $phone;
        public $sex;
        public $city;
        public $province;
        public $country;

        
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
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_current_user($id){
            $query = "SELECT `id`, `email`, `nick_name`, `phone`, `headImg`, `sex`, `city`, `province`, `country` FROM customers WHERE `id` = ? AND `status` = 'open'";
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

        public function save_wechat_uer($nickname, $sex, $city, $province, $country, $headImg, $unionid){
            $status = "open";
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO customers (`nick_name`, `sex`, `city`, `province`, `country`, `headImg`, `unionid`, `status`) VALUES (?,?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sissssss", $nickname, $sex, $city, $province, $country, $headImg, $unionid, $status);
                $sql->execute();
                $new_id = $this->conn->insert_id;
                $this->conn->commit();
                return $new_id;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_user($nickname, $email, $phone, $sex, $city, $province, $country, $user_id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "UPDATE customers SET nick_name = ?, email = ?, phone = ?, sex = ?, city = ?, province = ?, country = ? WHERE id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssisssi", $nickname, $email, $phone, $sex, $city, $province, $country, $user_id);
                $sql->execute();
                $this->conn->commit();
                return true;
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
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function fetch_all_users(){
            $data = array();
            $query = "SELECT `id`, `email`, `phone`, `nick_name`, `sex`, `city`, `province`, `country`, `headImg`, `unionId`, `temporary_link`, `status` FROM customers";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }
    }
?>