<?php
    require_once("../../public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result["categories"] = $program->fetch_program_data_all();
        $result["programs"] = $program->fetch_all_program_items();
        echo json_encode($result);
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["to"])){
        $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "$global_prefix/uploads/";
        $to = $_GET["to"];
        if($to == "newCategory"){
            $name = $_POST["category_name"];
            $status = $_POST["category_status"];
            $index = $_POST["category_index"];
            $imageUrl = "/uploads/" . basename($_FILES["category_img"]["name"]);
            $result["file"] = fileUpload($target_dir, "image", $_FILES["category_img"]);
            if($result["file"]["status"] != "failed"){
                $result["is_saved"] = $program->save_new_program_category($name, $imageUrl, $index, $status);
            }
            echo json_encode($result);
        }
        else if($to == "updateCategory"){
            $name = $_POST["category_name"];
            $status = $_POST["category_status"];
            $index = $_POST["category_index"];
            $id = $_POST["category_id"];
            if(!$_FILES["category_img"]["name"]){
                $result["is_saved"] = $program->update_program_category($name, "", $index, $status, $id);
            }
            else{
                $imageUrl = "/uploads/" . basename($_FILES["category_img"]["name"]);
                $file = $target_dir . basename($_FILES["category_img"]["name"]);
                if(file_exists($file)){
                    unlink($file);
                }
                $result["file"] = fileUpload($target_dir, "image", $_FILES["editCategoryImg"]);
                if($result["file"]["status"] != "failed"){
                    $result["is_saved"] = $program->update_program_category($name, $imageUrl, $index, $status, $id);
                }
            }
            echo json_encode($result);
        }
        else if($to == "updateProgram"){
            $name = $_POST["program_name"];
            $desc = !empty($_POST["program_desc"]) ? $_POST["program_desc"] : "";
            $related = !empty($_POST["program_related"]) ? $_POST["program_related"] : "";
            $status = $_POST["program_status"];
            $pc_id = $_POST["program_category_id"];
            $id = $_POST["program_id"];
            $is_updated = $program->update_program($name, $desc, $status, $related, $pc_id, $id);
            echo json_encode($is_updated);
        }
        else if($to == "newProgram"){
            $name = $_POST["program_name"];
            $desc = !empty($_POST["program_desc"]) ? $_POST["program_desc"] : "";
            $related = !empty($_POST["program_related"]) ? $_POST["program_related"] : "";
            $status = $_POST["program_status"];
            $pc_id = $_POST["program_category_id"];
            $is_saved = $program->save_program($name, $desc, $status, $related, $pc_id);
            echo json_encode($is_saved);
        }
    }
?>