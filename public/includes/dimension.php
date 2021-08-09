<?php
    class Dimension extends Database {
        public $dimensions = array();
        public $dimension_combinations = array();
        public $programs = array();

        public function fetchDimensionData(){
            $this->dimensions = $this->fetchDimensions();
            $this->dimension_combinations = $this->fetchDimensionCombinations();
            if(count($this->dimension_combinations) > 0){
                $new_combinations = array();
                foreach($this->dimension_combinations as $item){
                    $new_combination = $item;
                    $new_combination["programs"] = array();
                    $new_combination["characteristics"] = array();
                    $new_combination["career_analysis"] = array();
                    $new_combination["jobs"] = array();
                    $new_combination["programs"] = $this->fetchProgramsByDimensionCombinationId($item["id"]);
                    $new_combination["characteristics"] = $this->fetchCharacteristicsByDimensionCombinationId($item["id"]);
                    $new_combination["career_analysis"] = $this->fetchCareerAnalysisByDimensionCombinationId($item["id"]);
                    $new_combination["jobs"] = $this->fetchJobsByDimensionCombination($item["id"]);
                    array_push($new_combinations, $new_combination);
                }
                $this->dimension_combinations = $new_combinations;
            }
        }

        public function fetchDimensions(){
            $query = "SELECT `id`, `code`, `title`, `description`, compare_group FROM dimensions WHERE `status` = 'open'";
            return $this->fetchData($query);
        }

        private function fetchDimensionCombinations(){
            $query = "SELECT `id`, `code`, `title`, `description`, `basic_analysis` FROM dimension_combination";
            return $this->fetchData($query);
        }

        private function fetchProgramsByDimensionCombinationId($id){
            $query = "SELECT p.id, p.title, p.description FROM programs p INNER JOIN dimension_combination_program_junction j ON p.id = program_id INNER JOIN dimension_combination c ON c.id = j.dc_id WHERE c.id = ?";
            return $this->fetchDataById($query, $id);
        }

        private function fetchCharacteristicsByDimensionCombinationId($id){
            $query = "SELECT `id`, `dc_id`, `summary`, `description`, `type`, `item_index` FROM characteristics WHERE `dc_id` = ?";
            return $this->fetchDataById($query, $id);
        }

        private function fetchCareerAnalysisByDimensionCombinationId($id){
            $query = "SELECT `id`, `dc_id`, `title`, `description` FROM career_analysis WHERE `dc_id` = ?";
            return $this->fetchDataById($query, $id);
        }

        private function fetchJobsByDimensionCombination($id){
            $query = "SELECT `id`, `dc_id`, `title`, `description`, `item_index` FROM jobs WHERE `dc_id` = ? AND `status` = 'open'";
            return $this->fetchDataById($query, $id);
        }

        private function fetchDataById($query, $id){
            $data = array();
            $sql = $this->conn->prepare($query);
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($data, $row);
                }
            }
            return $data;
        }

        private function fetchData($query){
            $data = array();
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