<?php
require_once("../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../../vendor/phpmailer/phpmailer/src/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailBox extends Database
{
    public function send($receiver, $receiver2, $subject, $content)
    {
        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->SMTPAutoTLS = false;
            //$mail->SMTPDebug = 4; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'hwsmtp.exmail.qq.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username = 'contact@suitntie.cn';
            $mail->Password = 'gibJmdW5PszNRhVy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            //$mail->SMTPSecure = 'ssl';
            $mail->Port = 465; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom('contact@suitntie.cn', 'Do Not Reply');
            $mail->addAddress($receiver); // Add a recipient
            if (!empty(trim($receiver2))) {
                $mail->addAddress($receiver2);
            }
            //$mail->addAddress("wyao@alcanada.com");
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            $mail->AddEmbeddedImage($_SERVER["DOCUMENT_ROOT"] . '/asset/image/logo.png', 'logo'); // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $mail->Charset = 'UTF-8';
            $mail->Body    = $content;
            $mail->AltBody = $content;
            $mail->send();
            //callback("注册成功。邮件已发送到您的注册邮箱。", "success");
            return true;
        } catch (Exception $e) {
            //callback($e, "error");
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}
