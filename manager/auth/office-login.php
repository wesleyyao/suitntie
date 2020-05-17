<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    $result["message"] = array();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $redirect_to = isset($_GET["redirect"]) ? $_GET["redirect"] : '';
        if(!isset($_POST["email"]) || !isset($_POST["password"])){
            $result["message"]["type"] = "danger";
            $result["message"]["content"] = "required missing";
        }
        else{
            $email = $_POST["email"];
            $password = $_POST["password"];
            $found_user_id = $office->staff_login($email, $password);
            if($found_user_id){
                $_SESSION["login_staff"] = $found_user_id;

                $result["message"]["type"] = "success";
                $result["message"]["content"] = "登录成功！";
                //redirect(!empty($redirect_to) ? $redirect_to : '/index.php');
            }
            else{
                $result["message"]["type"] = "danger";
                $result["message"]["content"] = "dismatch";
            }
        }
        //redirect(!empty($redirect_to) ? $redirect_to : '/suitntie/manager/index.html');
    }
    else{
        $result["message"]["type"] = "danger";
        $result["message"]["content"] = "invalid request";
    }
    echo json_encode($result);
?>