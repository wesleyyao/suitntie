<?php
    require_once("../../utils/initial.php");
    require_once("../../public/includes/slider.php");
    $slider = new Slider();

    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["id"]) && isset($_GET["type"])){
        $id = $_GET["id"];
        $type = $_GET["type"];
        if(empty($_POST["slider_title"]) || empty($_POST["slider_content"]) || empty($_POST["slider_index"]) || empty($_POST["slider_type"]) || empty($_POST["slider_status"])){
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $slider_title = $_POST["slider_title"];
        $slider_content = $_POST["slider_content"];
        $slider_index = $_POST["slider_index"];
        $slider_type = $_POST["slider_type"];
        $slider_status = $_POST["slider_status"];
        $current_img = !empty($_POST["current_img"]) ? $_POST["current_img"] : "";
        $slider_link = !empty($_POST["slider_link"]) ? $_POST["slider_link"] : "";
        $slider_button = !empty($_POST["slider_button"]) ? $_POST["slider_button"] : "";
        $target_dir = $_SERVER["DOCUMENT_ROOT"] . "$global_prefix/uploads/";
        $imageUrl = "/uploads/" . basename($_FILES["slider_img"]["name"]);
        if(!$_FILES["slider_img"]["name"] && empty($current_img)){
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $file = $target_dir . (!empty($current_img) ? $current_img : basename($_FILES["slider_img"]["name"]));
        if(file_exists($file)){
            unlink($file);
        }
        if($type == "new"){
            $uploadResult = fileUpload($target_dir, "image", $_FILES["slider_img"]);
            if($uploadResult["status"] != "failed"){
                $is_saved = $slider->save_slider($slider_title, $imageUrl, $slider_content, $slider_index, $slider_type, $slider_status, $slider_link, $slider_button);
                $result = generateMessage($is_saved, "");
            }
            else{
                $result = generateMessage(false, $uploadResult["message"]);
            }
        }
        else{
            $uploadResult = array();
            if(!empty($current_img)){
                $uploadResult["status"] = "success";
            }
            else{
                $uploadResult = fileUpload($target_dir, "image", $_FILES["slider_img"]);
            }
            if($uploadResult["status"] != "failed"){
                $is_updated = $slider->update_slider($slider_title, $imageUrl, $slider_content, $slider_index, $slider_type, $slider_status, $slider_link, $slider_button, $id);
                $result = generateMessage($is_updated, "");
            }
            else{
                $result = generateMessage(false, $uploadResult["message"]);
            }
        }
    }
    echo json_encode($result);
    exit;

    function generateMessage($is_true, $message){
        $result = array();
        if($is_true == true){
            $result["status"] = "success";
            $result["content"] = "更新成功。";
        }
        else{
            $result["status"] = "warning";
            $result["content"] = $message;
        }
        return $result;
    }

    function invalidForm(){
        $result = array();
        $result["status"] = "warning";
        $result["content"] = "缺乏必须的字段。";
        return $result;
    }
?>