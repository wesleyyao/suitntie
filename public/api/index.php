<?php
    require_once("../includes/initial.php");
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["title"])){
            $title = $_GET["title"];
            if(!empty($title)){
                $data = array();
                $test->fetchData($title);
                //$customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
                $dimension->fetchDimensionData();
                $data["test"] = $test;
                $data["dimension"] = $dimension;
                //$data["user"] = $customer;
                echo json_encode($data);
            }
        }
    }
?>