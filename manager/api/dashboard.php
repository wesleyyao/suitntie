<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    $result["message"] = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result["users"] = $customer->fetch_all_users();
        $result["tests"] = $dimension_result->fetchNumberOfTestResults();
        $result["staff"] = $office->fetch_staff_data($_SESSION["login_staff"]);
        $result["test_lastWeek"] = $dimension_result->fetchNumberOfTestLastWeek();
        $result["test_lastMonth"] = $dimension_result->fetchNumberOfTestLastMonth();
        
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
