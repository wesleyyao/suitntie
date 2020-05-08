<?php
    require_once("../includes/initial.php");
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["title"])){
            $title = $_GET["title"];
            if(!empty($title)){
                $data = array();
                $test->fetchData($title);
                $dimension->fetchDimensionData();
                $data["test"] = $test;
                $data["dimension"] = $dimension;
                echo json_encode($data);
            }
        }
    }
?>