<?php
    require_once("../includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $data = array();
        if(isset($_GET["type"])){
            if($_GET["type"] == "all"){
                $data = $program->fetch_program_data();
            }
            else if($_GET["type"] == "categories"){
                $data = $program->fetch_category();
            }
        }
        else if(isset($_GET["view"]) && isset($_GET["title"])){
            $view = $_GET["view"];
            $title = $_GET["title"];
            if($view == "single"){
                $program->fetch_program_details($title);
                $data = $program;
            }
        }
        echo json_encode($data);
    }
?>