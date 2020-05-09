<?php
    session_start();
    require_once("database.php");
    $global_prefix = "/suitntie";
    function redirect($location){
        header("Location: {$location}");
        exit;
    }
?>