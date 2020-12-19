<?php
    require_once("../../utils/initial.php");
    require_once("../../public/includes/slider.php");
    $slider = new Slider();
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result = $slider->fetch_all_slider();
        echo json_encode($result);
    }
?>