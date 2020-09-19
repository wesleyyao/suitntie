<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["title"])){
        $title = $_GET["title"];
        $program->fetch_program_details_manager($title);
        echo json_encode($program);
    }
?>