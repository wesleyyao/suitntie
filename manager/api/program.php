<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result = $program->fetch_program_data();
        echo json_encode($result);
    }
?>