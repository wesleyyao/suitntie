<?php
    class Schools extends Database {
        public $id = 0;
        public $name = "";
        public $region = "";
        public $status = "";
        public $logo = "";
        public $ranking = "";

        public function fetchAll(){
            $data = array();
            $query = "SELECT * FROM schools2";
            $sql = $this->conn->prepare($query);
            if (!$sql->execute()) {
                return false;
            }
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
            return $data;
        }

        public function fetchSchoolData(){
            $data = array();
            $query = "SELECT `id`, `name` as 'university', `region` as 'country', `ranking` as 'rank', `logoPath` FROM schools2 WHERE `status` = 'open'";
            $sql = $this->conn->prepare($query);
            if (!$sql->execute()) {
                return false;
            }
            $result = $sql->get_result();
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
            return $data;
        }

        public function findSchoolByNameAndRegion(){
            $query = "SELECT `id` FROM schools WHERE `name` = ? AND region = ?";
            $sql = $this->conn->prepare($query);
            if (!$sql->execute()) {
                return false;
            }
            $result = $sql->get_result();
            $this->id = $result["id"];
            $this->name = $result["name"];
            $this->region = $result["region"];
            $this->status =$result["status"];
            $this->logo= $result["logo"];
            $this->ranking = $result["ranking"];
            
        }

        public function removeSchools(){
            $query = "DELETE FROM schools2";
            $sql = $this->conn->prepare($query);
            $sql->execute();
        }

        public function saveData($rank, $university, $country, $logoPath){
            $status = "open";
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO schools2 (`name`, region, ranking, logoPath, `status`) VALUES (?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("sssss", $university, $country, $rank, $logoPath, $status);
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
    }

?>