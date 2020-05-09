<?php
    class ProgramData extends Database {

        public function fetch_program_data(){
            $data = array();
            $categories = $this->fetch_category();
            if(count($categories) > 0){
                foreach($categories as $key){
                    $obj = $key;
                    $obj["details"] = array();
                    $obj["details"] = $this->fetch_programs_by_cateogry($obj["id"]);
                    array_push($data, $obj);
                }
            }
            return $data;
        }

        private function fetch_category(){
            $data = array();
            $query = "SELECT `id`, `name` FROM program_categories WHERE `status` = 'open'";
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

        private function fetch_programs_by_cateogry($pc_id){
            $data = array();
            $query = "SELECT `id`, `title`, `description` FROM programs WHERE `status` = 'open' AND pc_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $pc_id);
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