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
        if(isset($_POST["email"]) && isset($_POST["phone"])&& isset($_POST["name"])&& isset($_POST["wechat"])&& isset($_POST["city"])&& isset($_POST["school"])&& isset($_POST["content"])){
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $name = $_POST["name"];
            $wechat = $_POST["wechat"];
            $city = $_POST["city"];
            $school = $_POST["school"];
            $content = $_POST["content"];
            $is_saved = $message->save_contact_form($email, $phone, $name, $wechat, $city, $school, $content);
            if($is_saved){
                $email_content = emailContent($email, $phone, $name, $wechat, $city, $school, $content);
                $is_send = $mail->send($receiver, $receiver2, $subject, $email_content);
                echo json_encode(true);
                exit;
            }
            else{
                echo json_encode(false);
                exit;
            }
        }
        else{
            echo json_encode(false);
        }
    }
    else{
        echo json_encode(false);
    }

    function emailContent($email, $phone, $name, $wechat, $city, $school, $content){
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
        <body style='padding: 15px'>
            <div style='text-align: center'>
                <img class='logo' src='cid:logo' alt='logo'/>
                <h2>适途教育</h2>
            </div>
            <div style='width: 100%; padding-top: 30px;'>
                <h3 style='text-align: center'>用户咨询表</h3>
                <div style='text-align: center'> 
                    <p>用户: $name</p>
                    <p>电话：$phone</p>
                    <p>邮箱：$email</p>
                    <p>微信：$wechat</p>
                    <p>城市：$city</p>
                    <p>学校: $school</p>
                </div>
                <br/>
                <div style='text-align: center; width: 60%; margin-left: 20%; padding: 15px; border: 1px solid #f4f4f4; border-radius: 10px;'>
                $content
                </div>
            </div></body></html>";
    }
?>