<?php

    class Rankings extends Database {
        public $id = 0;
        public $university = "";
        public $rank = 0;
        public $country = "";
        public $logoPath = "";
        public $logoUrl = "";

        // public function __construct(){
        //     $this->fetchAllRankingData();
        // }

        public function fetchAllRankingData() {
            $data = array();
            $query = "SELECT * FROM program_rankings r INNER JOIN programs p ON p.id = r.p_id";
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

        public function saveRanking($id, $rank, $university, $country, $logoPath, $logoUrl, $pId){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "INSERT INTO program_rankings (`id`, `rank`, `university`, `country`, `logoPath`, `logoUrl`, `p_id`) VALUES (?,?,?,?,?,?,?)";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("ssssssi", $id, $rank, $university, $country, $logoPath, $logoUrl, $pId);
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

        public function removeRankingsByProgram($programId){
            $this->conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            try {
                $query = "DELETE FROM program_rankings WHERE p_id = ?";
                $sql = $this->conn->prepare($query);
                $sql->bind_param("i", $programId);
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