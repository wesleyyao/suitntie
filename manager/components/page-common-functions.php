<?php
    if(!isset($_SESSION["login_staff"])){
        header("Location: ./index.php");
        exit;
    }
    if(!isset($_GET["id"]) || !isset($_GET["type"]) || !isset($_GET["pid"]) || !isset($_GET["title"])){
        header("Location: ../programs.php");
        exit;
    }
?>