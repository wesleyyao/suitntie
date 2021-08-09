<?php
    require_once("../../utils/initial.php");
    require_once("../includes/ranking.php");
    require_once("../includes/school.php");
    $rankings = new Rankings();
    $schools = new Schools();
    $data = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $data["rankings"] = $rankings->fetchAllRankingData();
        $data["overall"] = $schools->fetchSchoolData();
    }
    echo json_encode($data);
    exit;
?>