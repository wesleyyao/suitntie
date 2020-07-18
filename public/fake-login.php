<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/initial.php");
    $_SESSION["login_user"] = 17;
    redirect("/tests/dimension-test.php");
?>