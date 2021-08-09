<?php
        require_once("../../utils/initial.php");
        unset($_SESSION["login_staff"]);
        redirect($global_prefix . '/manager/index.html');
?>