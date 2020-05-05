<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/customer.php");
    $customer = new Customer;
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(!isset($_GET["user"]) || !isset($_GET["tempId"])){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "认证链接错误。";
            redirect("/user.php");
            exit;
        }
        $email = trim($_GET["user"]);
        $temporary_link = trim($_GET["tempId"]);
        if(empty($email) || empty($temporary_link)){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "认证链接错误。";
            redirect("/user.php");
            exit;
        }
        $found_customer = $customer->find_user_id($email, $temporary_link);
        if($found_customer == NULL){
            $_SESSION["signup_message"]["type"] = "error";
            $_SESSION["signup_message"]["message"] = "用户不存在，无法完成注册";
            redirect("/user.php");
        }
        else{
            $customer->finish_signup($found_customer["id"]);
            $_SESSION["login_user"] = $found_customer["id"];
            $_SESSION["signup_message"]["type"] = "success";
            $_SESSION["signup_message"]["message"] = "注册成功，邮箱验证已通过。";
            redirect("/user.php");
        }
    }
?>