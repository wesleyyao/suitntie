<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    unset($_SESSION["login_user"]);
    $redirect_to = isset($_GET["redirect"]) ? $_GET["redirect"] : '';
    redirect(!empty($redirect_to) ? $redirect_to : '/index.php');
?>