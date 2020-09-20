<?php
    require_once("../../utils/initial.php");
    unset($_SESSION["login_user"]);
    $redirect_to = isset($_GET["redirect"]) ? $_GET["redirect"] : '';
    redirect(!empty($redirect_to) ? $redirect_to : $global_prefix . "/index.php");
?>