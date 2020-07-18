<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public/includes/customer.php");
    $customer = new Customer;
    $message = array();
    $current_time = date("Y-m-d H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(!isset($_GET["user"]) || !isset($_GET["tempId"]) || !isset($_GET["redirect"])){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "认证链接错误。";
            redirect($global_prefix . "/account/user.php");
            exit;
        }
        $url = trim($_GET["redirect"]);
        $email = trim($_GET["user"]);
        $temporary_link = trim($_GET["tempId"]);
        if(empty($email) || empty($temporary_link)){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "认证链接错误。";
            redirect($url);
            exit;
        }
        $found_customer = $customer->find_user_id($email, $temporary_link);
        if($found_customer == NULL){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "用户不存在，无法完成注册";
            redirect($url);
        }
        else{
            $customer->finish_signup($found_customer["id"]);
            $_SESSION["login_user"] = $found_customer["id"];
            $_SESSION["signup_message"]["type"] = "success";
            $_SESSION["signup_message"]["message"] = "注册成功，邮箱验证已通过。";
            redirect($url);
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["by"])){
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
            if(!isset($_POST["email"]) || !isset($_POST["password"])){
                $message["type"] = "danger";
                $message["content"] = "请填写所有必填内容。";
            }
            else{
                $email = trim($_POST["email"]);
                $password = trim($_POST["password"]);
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
                            $is_saved = $customer->save_new_email_user($email, $password, $current_time, $ip);
                            if($is_saved){
                                $_SESSION["login_user"] = $customer->id;
                                $message["type"] = "success";
                                $message["content"] = "您已经注册成功。正在为您跳转...";
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
    }
    echo json_encode(false);
?>