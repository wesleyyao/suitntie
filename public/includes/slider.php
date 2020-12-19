<?php
    class Slider extends Database {
        public function fetch_home_slider(){
            $data = array();
            $query = "SELECT `id`, `image`, `title`, `content`, `link`, `button`, `item_index`, `type`, `status` FROM sliders WHERE `status` = 'open' AND `type` = 'home' ORDER BY item_index";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function fetch_all_slider(){
            $data = array();
            $query = "SELECT * FROM sliders ORDER BY item_index";
            $sql = $this->conn->prepare($query);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function fetch_slider_by_id($id){
            $query = "SELECT `id`, `image`, `title`, `content`, `link`, `button`, `item_index`, `type`, `status` FROM sliders WHERE id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            return mysqli_fetch_assoc($result);
        }

        public function save_slider($title, $imageUrl, $content, $index, $type, $status, $link, $button){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO sliders (`title`, `image`, `content`, `item_index`, `type`, `status`, `link`, `button`) VALUES (?,?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssissss", $title, $imageUrl, $content, $index, $type, $status, $link, $button);
                $sql->execute();
                $this->conn->commit();
                return true;
            } catch (Exception $e) {
                $this->conn->rollback();
                return false;
            }
        }

        public function update_slider($title, $imageUrl, $content, $index, $type, $status, $link, $button, $id){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $query = "UPDATE sliders SET `title` = ?, `image` = ?, `content` = ?, `item_index` = ?, `type` = ?, `status` = ?, `link` = ?, `button` = ? WHERE `id` = ?";
            try {
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssissssi", $title, $imageUrl, $content, $index, $type, $status, $link, $button, $id);
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