<?php

class Database
{
    protected $conn;

    public function __construct()
    {
        $this->open_db_connection();
    }

    protected function open_db_connection()
    {
        $host_name = "localhost:8889"; //3308
        $database = "suitntie";
        $user_name = "root"; //root
        $password = "root"; //SuitNtie0502
        $this->conn = mysqli_connect($host_name, $user_name, $password, $database);
        $this->conn->set_charset("utf8");
    }

    public function validate_input($arr)
    {
        foreach ($arr as $val) {
            if (empty(trim($val))) {
                return false;
            }
        }
        return true;
    }

    protected function fetch_data_by_foreign_key($query, $id){
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

    public function reset_table_index($table){
        $query = "ALTER TABLE $table AUTO_INCREMENT = 1";
        $sql = $this->conn->prepare($query);
        $sql->execute();
    }
}