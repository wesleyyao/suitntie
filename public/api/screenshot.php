<?php
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["imgUrl"])){
        $image = $_POST["imgUrl"];
        $image = explode(";", $image)[1];
        $image = explode(",", $image)[1];
        $image = base64_decode($image);

        header("Content-type: image/jpeg");
        header('Content-Disposition: attachment; filename=screenshot.jpg');
        
        imagejpeg($image);
    }
?>