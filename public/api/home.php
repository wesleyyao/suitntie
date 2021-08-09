<?php
    require_once("../../utils/initial.php");
    require_once("../includes/slider.php");
    $slider = new Slider();
    $data = array();
    $data = $slider->fetch_home_slider();
    echo json_encode($data);
?>