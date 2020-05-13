<?php
    session_start();
    if(!isset($_SESSION["login_staff"])){
        header("Location: ./index.php");
        exit;
    }
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>管理页面</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require_once("./components/style.php"); ?>
    </head>
    <body>
        <div class="container">
            <div id="mainDiv">
                <?php require_once("./components/nav.php"); ?>
                <br/>
                <div id="message"></div>
                <div id="programMain">

                </div>
            </div>
        </div>
        <?php require_once("./components/login.php"); ?>
        <?php require_once("./components/script.php"); ?>
        <script src="/suitntie/manager/asset/js/program.js"></script>
    </body>
</html>