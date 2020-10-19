<?php
    class Consult extends Database {
        public $id;
        public $full_name = "";
        public $nick_name = "";
        public $region = "";
        public $program = array();
        public $education = "";
        public $introduction = "";
        public $thumbnail = "";
        public $educations = ["本科", "硕士", "博士"];

        public function fetch_consultants_with_schools_data(){
            $data = array();
            $consultants = $this->fetch_consultants();
            if(count($consultants) > 0){
                foreach($consultants as $item){
                    $temp_obj = $item;
                    $temp_obj["programs"] = $this->fetch_consultants_programs($item["id"]);
                    array_push($data, $temp_obj);
                }
            }
            return $data;
        }

        public function fetch_consultants(){
            $data = array();
            $query = "SELECT `id`, `name`, `nick_name`, `education`, `introduction`, `thumbnail` FROM tutors WHERE `status` = 'open'";
            $sql = $this->conn->prepare($query);
            if(!$sql->execute()){
                return [];
            }
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }

        private function fetch_consultants_programs($id){
            $data = array();
            $query = "SELECT t.school_id, t.program_id, t.scholarship, t.education, t.id, p.title, p.description, p.related, s.name as 'school', s.region, r.name as 'region_name' FROM tutor_program_junction t INNER JOIN programs p ON p.id = t.program_id INNER JOIN schools s ON s.id = t.school_id INNER JOIN tutor_region r ON r.id = s.region WHERE t.tutor_id = ?";
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            if(!$sql->execute()){
                return [];
            }
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }
        
        private function fetch_schools(){
            $data = array();
            $query = "SELECT `id`, `name`, `region` FROM schools WHERE `status` = 'open'";
            $sql = $this->conn->prepare($query);
            if(!$sql->execute()){
                return false;
            }
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }

        public function fetch_regions(){
            $data = array();
            $query = "SELECT `id`, `name` FROM tutor_region WHERE `status` = 'open' ORDER BY r_index ASC";
            $sql = $this->conn->prepare($query);
            if(!$sql->execute()){
                return [];
            }
            $result = $sql->get_result();
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            return $data;
        }
    }
?>