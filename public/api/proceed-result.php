<?php
require_once("../includes/initial.php");
$result_category = "dimension_test_result";
$current_time = date("Y-m-d H:i:s");
$receiver = "tim.zhao@suitntie.cn";
$receiver2 = "fangqian@suitntie.cn";
$subject = "适途教育[通知] - 内部邮件 - 新测试结果";
$result = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["login_user"])) {
        echo json_encode("no login");
        exit;
    }
    if (!isset($_POST["result_codes"]) || !isset($_POST["test_id"])) {
        echo json_encode("invalid form");
        exit;
    }
    $result_codes = trim($_POST["result_codes"]);
    $test_id = trim($_POST["test_id"]);
    if (empty($result_codes) || empty($test_id)) {
        echo json_encode("invalid form");
        exit;
    }
    $dimension_ids = array();
    $dimension_check_times = array();

    foreach ($_POST as $key => $val) {
        if (strpos($key, "dimension_") !== false) {
            $dimension_id = intval(str_replace("dimension_", "", $key));
            array_push($dimension_ids, $dimension_id);
            array_push($dimension_check_times, $val);
        }
    }
    $user_name = $_POST["userName"];
    $user_age = $_POST["userAge"];
    $purpose = $_POST["purpose"];
    $how_know = $_POST["knownBy"];

    if (count($dimension_ids) == 0) {
        echo json_encode("failure");
        exit;
    }
    if (!isset($_SESSION["new_test_result"])) {
        $new_result_id = $test->saveResult($test_id, $result_category, $_SESSION["login_user"], $dimension_ids, $dimension_check_times, $user_name, $user_age, $purpose, $how_know, $current_time);
        if ($new_result_id) {
            $_SESSION["new_test_result"] = $new_result_id;
            //send email
            //$customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
            //$dimension_result->fetchResult($new_result_id);
            //$email_content = emailContent($customer->nick_name, $dimension_result->code, $dimension_result->title, $customer->email, $customer->phone);
            //$is_send = $email->send($receiver, $receiver2, $subject, $email_content);
            unset($_SESSION["new_test"]);
        } else {
            $_SESSION["test_error"] = "测试结果保存未成功。";
        }
        echo json_encode("success");
        exit;
    } else {
        echo json_encode("finished request");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        if ($action == "send_email") {
            $new_result_id = $_SESSION["new_test_result"];
            $_SESSION["is_sent_new_result"] = false;
            if (!$_SESSION["is_sent_new_result"]) {
                $customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
                $dimension_result->fetchResult($new_result_id);
                $email_content = emailContent($customer->nick_name, $dimension_result->code, $dimension_result->title, $customer->email, $customer->phone, $customer->full_name, $customer->age, $customer->is_study_aboard, $customer->how_know);
                $is_send = $email->send($receiver, $receiver2, $subject, $email_content);
                //$is_send = true;
                $_SESSION["is_sent_new_result"] =  $is_send ? true : false;
                $result["status"] = $is_send ? "sent" : "failure";
            } else {
                $result["status"] = "sent";
            }
        }
    }
    echo json_encode($result);
    exit;
}

function test_error_redirect($message)
{
    $_SESSION["test_error_message"] = $message;
    redirect("/tests/dimension-test.php");
    exit;
}

function emailContent($nick_name, $code, $title, $email, $phone, $full_name, $age, $is_study_aboard, $how_know)
{
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
                    <span>用户昵称 <b>$nick_name</b> 测得结果组合 <b>$code ： $title</b></span>
                </div>
                <br/>
                <h4 style='text-align: center'>用户信息</h4>
                <div style='text-align: center'>
                    <label>电话： $phone</label>
                    <br/>
                    <label>邮箱: $email</label>
                    <br/>
                    <label>全名: $full_name</label>
                    <br/>
                    <label>年龄: $age</label>
                    <br/>
                    <label>我目前: $is_study_aboard</label>
                    <br/>
                    <label>从哪里知道我们: $how_know</label>
                </div>
            </div></body></html>";
}
