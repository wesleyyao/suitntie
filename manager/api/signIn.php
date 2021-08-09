<?php
    require_once("../../public/includes/initial.php");
    $result = array();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_POST["email"]) || !isset($_POST["password"])){
            $result["type"] = "danger";
            $result["content"] = "未获得邮箱或密码。";
        }
        else{
            $email = $_POST["email"];
            $password = $_POST["password"];
            $found_user_id = $office->staff_login($email, $password);
            if($found_user_id){
                $_SESSION["login_staff"] = $found_user_id;

                $result["type"] = "success";
                $result["content"] = "登录成功！正在为您加载数据...";
            }
            else{
                $result["type"] = "danger";
                $result["content"] = "用户名或密码错误。";
            }
        }
    }
    else{
        $result["type"] = "danger";
        $result["content"] = "请求不合法。";
    }
    echo json_encode($result);
?>