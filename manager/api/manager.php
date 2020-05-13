<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    $result = array();
    $result["message"] = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(!isset($_SESSION["login_staff"])){
            $result["message"]["type"] = "danger";
            $result["message"]["content"] = "no login";
        }
        else{
            $result["users"] = $customer->fetch_all_users();
            $result["staff"] = $office->fetch_staff_data($_SESSION["login_staff"]);
        }
        if(isset($_GET["user"])){
            $userId = $_GET["user"];
            $result = $office->fetch_test_results($userId);
            echo json_encode($result);
            exit;
        }
        if(isset($_GET["result"])){
            $result_id = $_GET["result"];
            $dimension_result->fetchResult($result_id);
            echo json_encode($dimension_result);
            exit;
        }
    }
    else{
        $result["message"]["type"] = "danger";
        $result["message"]["content"] = "invalid request";
    }
    echo json_encode($result);
    exit;
?>