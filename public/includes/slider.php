<?php
    class Slider extends Database {
        public function fetch_home_slider(){
            $data = array();
            $query = "SELECT image, `title`, `content`, `link`, `button` FROM sliders WHERE `status` = 'open' AND `type` = 'home' ORDER BY item_index";
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
    }
?>