<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result["status"] = isset($_SESSION["login_staff"]) ? "success" : "danger";
    }
    echo json_encode($result);
    exit;
?>