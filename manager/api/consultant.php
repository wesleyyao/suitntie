<?php
require_once("../../utils/initial.php");
require_once("../../public/includes/consult.php");
require_once("../../public/includes/programs.php");
$consult = new Consult();
$program = new ProgramData();
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["tutor"])) {
    $tutor = $_GET["tutor"];
    $data = array();
    if(!isset($_SESSION["login_staff"])){
        $data["status"] = "NO_LOGIN";
        echo json_encode($data);
        exit;
    }
    if ($tutor == "all") {
        $data["consultants"] = $consult->fetch_all_consultants();
    } else {
        $data["consultant"] = $consult->fetch_consultant_by_id($tutor);
        $data["consultant"]["programs"] = $consult->fetch_consultant_program_by_id($tutor);
        $data["programs"] = $program->fetch_all_program_items();
    }
    $data["status"] = "LOGIN";
    $data["schools"] = $consult->fetch_all_schools();
    $data["regions"] = $consult->fetch_all_regions();
    echo json_encode($data);
}
