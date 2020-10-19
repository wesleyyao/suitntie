<?php
    require_once("../../utils/initial.php");
    require_once("../includes/consult.php");
    require_once("../includes/programs.php");
    $programs = new ProgramData();
    $consults = new Consult();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result = array();
        $result["consultants"] = $consults->fetch_consultants_with_schools_data();
        $result["references"] = array( 'regions' => $consults->fetch_regions(), 'programs' => $programs->fetch_program_data_all(), 'educations' => $consults->educations);
        echo json_encode($result);
    }
    else{
        echo json_encode([]);
    }
?>