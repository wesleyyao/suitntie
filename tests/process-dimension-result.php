<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    $result_category = "dimension_test_result";
    $current_time = date("Y-m-d H:i:s");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_SESSION["login_user"])){
            test_error_redirect('您没有登录。请点击<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>。');
        }
        if(!isset($_POST["result_codes"]) || !isset($_POST["test_id"])){
            test_error_redirect("表格异常，请求无法被处理，请重试。");
        }
        $result_codes = trim($_POST["result_codes"]);
        $test_id = trim($_POST["test_id"]);
        if(empty($result_codes) || empty($test_id)){
            test_error_redirect("表格异常，请求无法被处理，请重试。");
        }
        $dimension_ids = array();
        $dimension_check_times = array();

        foreach($_POST as $key => $val){
            if(strpos($key, "dimension_") !== false){
                $dimension_id = intval(str_replace("dimension_", "", $key));
                array_push($dimension_ids, $dimension_id);
                array_push($dimension_check_times, $val);
            }
        }
        if(count($dimension_ids) == 0){
            test_error_redirect("页面异常，请求无法被处理，请重试。");
        }
        if(isset($_SESSION["new_test"])){
            $new_result_id = $test->saveResult($test_id, $result_category, $_SESSION["login_user"], $dimension_ids, $dimension_check_times, $current_time);
            if($new_result_id){
                $_SESSION["new_test_result"] = $new_result_id;
                $_SESSION["new_test_saved_notification"] = 1;
                unset($_SESSION["new_test"]);
            }
            else{
                $_SESSION["test_error"] = "测试结果保存未成功。";
            }
            redirect($new_result_id ? "dimension-test-result.php" : "index.php");
        }
        else{
            redirect("dimension-test-result.php");
        }
    }
    else{
        redirect('dimension-test-result.php');
    }

    function test_error_redirect($message){
        $_SESSION["test_error_message"] = $message;
        redirect("index.php");
        exit;
    }
?>