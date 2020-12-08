<?php
    require_once("../../utils/initial.php");
    require_once("../includes/customer.php");
    $customer = new Customer;
    $message = array();
    $current_time = date("Y-m-d H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];
    $result = array();
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        redirect("../../index.html");
        exit;
    }
    if(!isset($_POST["by"])){
        $message["type"] = "danger";
        $message["content"] = "无法处理该请求。";
    }
    $by = $_POST["by"];
    if($by == "phone"){
        if(!isset($_SESSION["login_user"])){
            $message["type"] = "danger";
            $message["content"] = "您的登录信息已经过期，请重新登录。";
        }
        else{
            $user_id = $_SESSION["login_user"];
            if(!isset($_POST["phone"]) || empty(trim($_POST["phone"]))){
                $message["type"] = "danger";
                $message["content"] = "请填写所有必填内容。";
            }
            else{
                $phone = trim($_POST["phone"]);
                $is_found = $customer->find_user_by_phone($phone);
                if($is_found){
                    $is_transfer = $customer->transfer_user_data_by_phone($phone, $user_id);
                    $message["type"] = $is_transfer ? "success" : "warning";
                    $message["content"] = $is_transfer ? "找到您用手机号注册的账户，该账户的数据已经合并到此微信账户下。" : "未保存成功，请刷新后充实重试。";
                }
                else{
                    $is_saved = $customer->save_phone_by_user($phone, $user_id);
                    $message["type"] = $is_saved ? "success" : "warning";
                    $message["content"] = $is_saved ? "您的手机号已经保存成功。正在为您跳转..." : "未保存成功，请刷新后重试。";
                }
            }
        }
    }
    else if($by == "email"){ 
        if(!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["redirectTo"])){
            $message["type"] = "danger";
            $message["content"] = "请填写所有必填内容。";
        }
        else{
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
            $request_url = trim($_POST["redirectTo"]);
            $register_by = "";
            if(strpos($request_url, "programs/explore") > -1){
                $register_by = "专业";
            }
            if(strpos($request_url, "tests/dimension-test") > -1){
                $register_by = "测试";
            }
            if(empty($email) || empty($password)){
                $message["type"] = "danger";
                $message["content"] = "请填写所有必填内容。"; 
            }
            else{
                $is_found = $customer->find_user_by_email($email);
                if(!$is_found){
                    $message["type"] = "danger";
                    $message["content"] = "请求无法被处理，请重试"; 
                }
                else{
                    if($is_found == "not found"){
                        $is_saved = $customer->save_new_email_user($email, $password, $current_time, $ip, $register_by);
                        if($is_saved){
                            $_SESSION["login_user"] = $customer->id;
                            $message["type"] = "success";
                            $message["content"] = "您已经注册成功。正在为您跳转...";
                            $message["url"] = $_SERVER["REQUEST_URI"];
                        }
                        else{
                            $message["type"] = "warning";
                            $message["content"] = "保存失败，请稍后重试。";
                        }
                    }
                }
            }
        }
    }
    echo json_encode($message);
    exit;
?>