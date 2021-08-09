<?php
    class Message extends Database{
        public function save_contact_form($email, $phone, $name, $wechat, $city, $school, $content, $age, $consultant){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO inquiry (`name`, `email`, `phone`, `wechat`, `city`, `school`, `content`, `age`, `consultant`) VALUES (?,?,?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssssssis", $name, $email, $phone, $wechat, $city, $school, $content, $age, $consultant);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }
    }
?>