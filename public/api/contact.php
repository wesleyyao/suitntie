<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
    }
    else{
        echo json_encode("bad request");
    }

?>