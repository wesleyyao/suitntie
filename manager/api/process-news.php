<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/slider.php");
    $slider = new Slider();
    if(!isset($_SESSION["login_staff"])){
        redirect("../index.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["operate"])){
        $type = $_GET["operate"];
        $id = isset($_GET["id"]) ? $_GET["id"] : 0;
        if(empty($_POST["slider_title"]) || empty($_POST["slider_content"]) || empty($_POST["slider_index"]) || empty($_POST["slider_type"]) || empty($_POST["slider_status"])){
            invalidForm($back_url);
        }
        $slider_title = $_POST["slider_title"];
        $slider_content = $_POST["slider_content"];
        $slider_index = $_POST["slider_index"];
        $slider_type = $_POST["slider_type"];
        $slider_status = $_POST["slider_status"];
        $slider_link = !empty($_POST["slider_link"]) ? $_POST["slider_link"] : "";
        $slider_button = !empty($_POST["slider_button"]) ? $_POST["slider_button"] : "";
        $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "/suitntie/uploads/";
        $imageUrl = "/uploads/" . basename($_FILES["slider_img"]["name"]);
        $file = $target_dir . basename($_FILES["slider_img"]["name"]);
        if(file_exists($file)){
            unlink($file);
        }
        $result["file"] = fileUpload($target_dir, "image", $_FILES["slider_img"]);
        if($type == "new"){
            if($result["file"]["status"] != "failed"){
                $is_saved = $slider->save_slider($slider_title, $imageUrl, $slider_content, $slider_index, $slider_type, $slider_status, $slider_link, $slider_button);
                generateMessage($is_saved);
            }
            else{
                generateMessage(false);
            }
        }
        else{
            if($result["file"]["status"] != "failed"){
                $is_updated = $slider->update_slider($slider_title, $imageUrl, $slider_content, $slider_index, $slider_type, $slider_status, $slider_link, $slider_button, $id);
                generateMessage($is_updated);
            }
            else{
                generateMessage(false);
            }
        }
        redirect("../components/slider.php?type=$type&id=$id");
    }

    function generateMessage($is_true){
        if($is_true == true){
            $_SESSION["slider_message"]["status"] = "success";
            $_SESSION["slider_message"]["content"] = "更新成功。";
        }
        else{
            $_SESSION["slider_message"]["status"] = "warning";
            $_SESSION["slider_message"]["content"] = "更新失败。";
        }
    }

    function invalidForm($back_url){
        $_SESSION["slider_message"]["status"] = "warning";
        $_SESSION["slider_message"]["content"] = "缺乏必须的字段。";
        redirect("$back_url");
    }
?>