<?php
    require_once("../../utils/initial.php");
    require_once("../../public/includes/slider.php");
    $slider = new Slider();
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(!isset($_SESSION["login_staff"])){
            $result["status"] = "NO_LOGIN";
            echo json_encode($result);
            exit;
        }
        $result["sliders"] = $slider->fetch_all_slider();
        $result["status"] = "LOGIN";
        echo json_encode($result);
    }
?>