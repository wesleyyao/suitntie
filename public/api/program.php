<?php
    require_once("../includes/initial.php");
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $data = $program->fetch_program_data();
        echo json_encode($data);
    }
?>