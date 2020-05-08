<?php
    class Message extends Database{
        public function save_contact_form($email, $phone, $name, $wechat, $city, $school, $content){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO inquiry (`name`, `email`, `phone`, `wechat`, `city`, `school`, `content`) VALUES (?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssssss", $name, $email, $phone, $wechat, $city, $school, $content);
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