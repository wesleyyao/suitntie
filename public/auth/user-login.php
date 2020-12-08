<?php
    require_once("../includes/initial.php");
    $message = array();
    $current_time = date("Y-m-d H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["by"])){
        $by = $_POST["by"];
        if($by == "phone"){
            if(!isset($_POST["phone"]) || empty(trim($_POST["phone"])) || !isset($_POST["currentPage"])){
                $message["type"] = "error";
                $message["content"] = "请求错误，请刷新后重试";
            }
            $phone = trim($_POST["phone"]);
            $request_url = trim($_POST["currentPage"]);
            $is_found = $customer->find_user_by_phone($phone);
            if($is_found){
                $_SESSION["login_user"] = $customer->id;
                
                $message["type"] = "success";
                $message["content"] = "您登录成功，正在为您准备数据...";
            }
            else{
                $register_by = "";
                if(strpos($request_url, "programs/explore") > -1){
                    $register_by = "专业";
                }
                if(strpos($request_url, "tests/dimension-test") > -1){
                    $register_by = "测试";
                }
                $is_saved = $customer->signup_by_phone($phone, $current_time, $ip, $register_by);
                if($is_saved){
                    $_SESSION["login_user"] = $customer->id;
                }
                $message["type"] = $is_saved ? 'success' : 'danger';
                $message["content"] = $is_saved ? "您登录成功，正在为您准备数据..." : "请求无法被处理，请刷新后重试。";
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
                $found_user_id = $customer->validate_login($email, $password);
                if($found_user_id){
                    $_SESSION["login_user"] = $found_user_id;
                    $message["type"] = "success";
                    $message["content"] = "您登录成功，正在为您准备数据...";
                }
                else{
                    $message["type"] = "warning";
                    $message["content"] = "未找到该用户，请填写有效的信息。";
                }
            }
        }
        echo json_encode($message);
    }
    else{
        echo json_encode(false);
    }
?>