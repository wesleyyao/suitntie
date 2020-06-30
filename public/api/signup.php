<?php
    require_once("../includes/initial.php");
    $subject = "适途教育 - 新用户注册";
    $message = array();
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["by"])){
        $by = $_GET["by"];
        if($by == "email"){
            if(!isset($_POST["email"]) || empty(trim($_POST["email"])) || !isset($_POST["code"])){
                $message["type"] = "warning";
                $message["content"] = $_POST["email"];
            }
            else{
                $email_addr = trim($_POST["email"]);
                $code = $_POST["code"];
                $is_found = $customer->find_user_by_email($email_addr);
                if(!$is_found){
                    $message["type"] = "warning";
                    $message["content"] = "无法处理您的请求，请刷新后重试。";
                }
                else if($is_found == "not found"){
                    $content = generateEmailContent($email_addr, $code);
                    $is_send = $email->send($email_addr, "", $subject, $content);
                    if($is_send){
                        $message["type"] = "success";
                        $message["content"] = "验证码已经成功发送到您的邮箱，该验证码的有效期为5分钟。";
                    }
                    else{
                        $message["type"] = "warning";
                        $message["content"] = "验证码未成功发送，请重试。";
                    }
                }
                else{
                    $message["type"] = "warning";
                    $message["content"] = "该邮箱已经注册过。";
                }
            }
        }
        echo json_encode($message);
        exit;
    }
    echo json_encode(false);
    exit;

    function callback($content, $type){
        $message["content"] = $content;
        $message["type"] = $type;
        echo json_encode($message);
        exit;
    }

    function generateEmailContent($email, $code) {
        return "<html>
            <head>
            <meta charset='utf-8'>
            <style>
                .logo{
                    width: 10%;
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
                    <h3 style='text-align: center'>用户注册 - 验证邮箱</h3>
                    <div style='text-align: center'>
                        <h4>验证码: $code</h4>
                        <p>请在五分钟内完成注册</p>
                    </div>
            </div></body></html>";
    }
?>