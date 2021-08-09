<?php
    require_once("../includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(!isset($_SESSION["login_user"])){
            echo json_encode("no login");
            exit;
        }
        if(isset($_SESSION["new_test_result"]) || isset($_GET["result"])){
            $result_id = isset($_SESSION["new_test_result"]) ? $_SESSION["new_test_result"] : (isset($_GET["result"]) ? $_GET["result"] : 0);
            if(!empty($result_id)){
                $data = array();
                if(isset($_SESSION["new_test_result"]) && isset($_GET["result"])){
                    unset($_SESSION["new_test_result"]);
                }
                if(isset($_SESSION["new_test_result"])){
                    $data["saved_notification"] = 1;
                }
                $dimension_result->fetchResult($result_id);
                $customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
                //$dimension->fetchDimensionData();
                $data["result"] = $dimension_result;
                $data["user"] = $customer;
                echo json_encode($data);
                exit;
            }
            else{
                echo json_encode("no result");
                exit;
            }
        }
        else{
            echo json_encode("no result");
            exit;
        }
    }
?>