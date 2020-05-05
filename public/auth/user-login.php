<?php
    require_once("../includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $redirect_to = isset($_GET["redirect"]) ? $_GET["redirect"] : '';
        if(!isset($_POST["login_email"]) || !isset($_POST["login_password"])){
            $_SESSION["auth_message"]["type"] = "error";
            $_SESSION["auth_message"]["message"] = "验证错误，请重试。";
        }
        else{
            $email = $_POST["login_email"];
            $password = $_POST["login_password"];
            $found_user_id = $customer->validate_login($email, $password);
            if($found_user_id){
                $_SESSION["login_user"] = $found_user_id;
                redirect(!empty($redirect_to) ? $redirect_to : '/index.php');
            }
            else{
                $_SESSION["auth_message"]["type"] = "error";
                $_SESSION["auth_message"]["message"] = "未找到该用户，请重试。";
            }
        }
        redirect(!empty($redirect_to) ? $redirect_to : '/index.php');
    }
?>