<?php
    class OfficeUser extends Database {
        public $id;
        public $name;
        public $title;
        public $department;
        public $permission;
        public $status;

        public function staff_login($email, $password){
            $query = "SELECT `id`, `password` FROM office WHERE email = ?";
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

        public function fetch_staff_data($id){
            $query = "SELECT `name`, `title`, `department`, `permission`, `status` FROM office WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        public function fetch_test_results($userId){
            $data = array();
            $query = "SELECT r.id, r.test_id, r.create_date, r.status, t.title FROM tests t INNER JOIN test_results r ON t.id = r.test_id WHERE r.user_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $userId);
            $sql->execute();
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }
    }
?>