<?php
    require_once("../includes/initial.php");
    $subject = "适途教育 - 新用户注册";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $message = array();
        if(!isset($_POST["submit_signup"]) || !isset($_GET["redirect"])){
            callback("请求无法被处理。请刷新页面后重试。", "error");
        }
        if(!isset($_POST["usr_email"]) || !isset($_POST["usr_password"]) || !isset($_POST["usr_phone"])){
            callback("请求无法被处理。请核实您的注册信息是否符合要求。", "error");
        }
        $redirect = $_GET["redirect"];
        $usr_email = trim($_POST["usr_email"]);
        $usr_password = trim($_POST["usr_password"]);
        $usr_phone = trim($_POST["usr_phone"]);
        if(empty($usr_email) || empty($usr_password)){
            callback("必填项是空白的。", "error");
        }
        $tempId = uniqid('', true);
        $result = $customer->user_signup($usr_email, $usr_phone, $usr_password, $tempId);
        if($result === "existed"){
            callback("该邮箱已经被注册。", "error");
        }
        else if(!$result){
            callback("注册失败，请重试。", "error");
        }
        else{
            $email_content = generateEmailContent($usr_email, $tempId, $redirect);
            $is_send = $email->send($usr_email, $subject, $email_content);
            callback($is_send ? "验证邮件已经发送到您的邮箱。" : "验证邮箱未能发送成功，请重试。", $is_send ? "success" : "error");
        }
    }

    function callback($content, $type){
        $message["content"] = $content;
        $message["type"] = $type;
        echo json_encode($message);
        exit;
    }

    function generateEmailContent($email, $tempId, $url) {
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
                        请点击以下链接，完成验证 
                        <a href='http://www.suitntie.cn/suitntie/public/auth/user-signup.php?user=$email&tempId=$tempId&redirect=$url'>
                            http://www.suitntie.cn/suitntie/public/auth/user-signup.php?user=$email&tempId=$tempId&redirect=$url
                        </a>
                    </div>
            </div></body></html>";
    }
?>