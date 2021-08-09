<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["title"])){
        $title = $_GET["title"];
        if(!isset($_SESSION["login_staff"])){
            $result["status"] = "NO_LOGIN";
            echo json_encode($result);
            exit;
        }
        $program->fetch_program_details_manager($title);
        $result = $program;
        $result->status = "LOGIN";
        echo json_encode($result);
    }
?>