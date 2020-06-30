<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    $result_category = "dimension_test_result";
    $current_time = date("Y-m-d H:i:s");
    $receiver = "tim.zhao@suitntie.cn";
    $receiver2 = "fangqian@suitntie.cn";
    $subject = "适途教育[通知] - 内部邮件 - 新测试结果";
    
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
                //send email
                $customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
                $dimension_result->fetchResult($new_result_id);
                $email_content = emailContent($customer->nick_name, $dimension_result->code, $dimension_result->title, $customer->email, $customer->phone);
                $is_send = $email->send($receiver, $receiver2, $subject, $email_content);

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

    function emailContent($nick_name, $code, $title, $email, $phone){
        return "<html>
        <head>
        <meta charset='utf-8'>
        <style>
            .logo{
                width: 20%;
                height: auto;
            }
        </style>
        </head>
        <body style='padding: 15px'>
            <div style='text-align: center'>
                <img class='logo' src='cid:logo' alt='logo'/>
                <h2>适途教育</h2>
            </div>
            <div style='width: 100%; padding-top: 30px;'>
                <h3 style='text-align: center'>用户的新测试结果</h3>
                <div style='text-align: center'> 
                    用户 $nick_name 测得结果组合 $code ： $title
                </div>
                <br/>
                <h4 style='text-align: center'>用户信息</h4>
                <div style='text-align: center'>
                    <label>电话： $phone</label>
                    <br/>
                    <label>邮箱: $email</label>
                </div>
            </div></body></html>";
    }
?>