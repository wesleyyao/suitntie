<?php
    session_start();
    require_once("database.php");
    function redirect($location){
        header("Location: {$location}");
        exit;
    }
?>