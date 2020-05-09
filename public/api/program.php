<?php
    require_once("../includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"])){
        $data = array();
        if($_GET["type"] == "all"){
            $data = $program->fetch_program_data();
        }
        else if($_GET["type"] == "categories"){
            $data = $program->fetch_category();
        }
        echo json_encode($data);
    }
?>