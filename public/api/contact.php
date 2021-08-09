<?php
    require_once("../../utils/initial.php");
    require_once("../includes/message.php");
    require_once("../includes/email.php");
    $message = new Message();
    $mail = new MailBox();
    $receiver = "tim.zhao@suitntie.cn";
    $receiver2 = "fangqian@suitntie.cn";
    $subject = "适途教育[通知] - 内部邮件 - 用户咨询";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_GET["type"])){
            echo json_encode(false);
            exit;
        }
        $type = $_GET["type"];
        if($type == "general"){
            if(isset($_POST["email"]) && isset($_POST["phone"])&& isset($_POST["name"])&& isset($_POST["wechat"])&& isset($_POST["city"])&& isset($_POST["school"])&& isset($_POST["content"])){
                $email = $_POST["email"];
                $phone = $_POST["phone"];
                $name = $_POST["name"];
                $wechat = $_POST["wechat"];
                $city = $_POST["city"];
                $school = $_POST["school"];
                $content = $_POST["content"];
                $is_saved = $message->save_contact_form($email, $phone, $name, $wechat, $city, $school, $content, "", "");
                if($is_saved){
                    $email_content = emailContent($email, $phone, $name, $wechat, $city, $school, $content, NULL, "", "general");
                    $is_send = $mail->send($receiver, $receiver2, $subject, $email_content);
                    //$is_send = $mail->send('westitotato@gmail.com', '', $subject, $email_content);
                    echo json_encode(true);
                    exit;
                }
                else{
                    echo json_encode(false);
                    exit;
                }
            }
        }
        else if($type == "consultant"){
            if(isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["name"]) && isset($_POST["age"]) && isset($_POST["school"]) && isset($_POST["content"])){
                $email = $_POST["email"];
                $phone = $_POST["phone"];
                $name = $_POST["name"];
                $wechat = isset($_POST["wechat"]) ? $_POST["wechat"] : "";
                $age = (int)$_POST["age"];
                $school = $_POST["school"];
                $content = $_POST["content"];
                $consultant = isset($_POST["consultant"]) ? $_POST["consultant"] : "";
                $is_saved = $message->save_contact_form($email, $phone, $name, $wechat, "", $school, $content, $age, $consultant);
                if($is_saved){
                    $email_content = emailContent($email, $phone, $name, $wechat, "", $school, $content, $age, $consultant, "consult");
                    $is_send = $mail->send($receiver, $receiver2, $subject, $email_content);
                    //$is_send = $mail->send("westitotato@gmail.com", "", $subject, $email_content);
                    echo json_encode(true);
                    exit;
                }
                else{
                    // $data["status"] = "error";
                    // $data["message"] = "data was not saved";
                    echo json_encode(false);
                    exit;
                }
            }
        }
        else{
            echo json_encode(false);
        }
    }
    else{
        echo json_encode(false);
    }

    function emailContent($email, $phone, $name, $wechat, $city, $school, $content, $age, $consultant, $type){
        $contactInfo = $type == "general" ? "<li>城市: $city</li>" : "<li>年龄: $age</li><li>导师: $consultant</li>";
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
        <body style='padding: 15px; width: 100%; background-color: #fdf7f2;'>
            <div style='text-align: center'>
                <img class='logo' src='cid:logo' alt='logo'/>
                <h2>适途教育</h2>
            </div>
            <div style='width: 100%; text-align: center; padding-top: 30px;'>
                <h3 style='text-align: center'>用户咨询表</h3>
                <ul> 
                    <li>用户: $name</li>
                    <li>电话：$phone</li>
                    <li>邮箱：$email</li>
                    <li>微信：$wechat</li>
                    <li>学校: $school</li>" . $contactInfo .
                "</div>
                <br/>
                <h5 style='text-align: center'>咨询内容</h5>
                <div style='width: 80%; margin-left: 10%; padding: 15px; border: 1px solid #f4f4f4; border-radius: 10px;'>
                $content
                </div>
            </div></body></html>";
    }
?>