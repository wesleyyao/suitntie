<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    $result = array();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $result["categories"] = $program->fetch_program_data_all();
        $result["programs"] = $program->fetch_all_program_items();
        echo json_encode($result);
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["to"])){
        $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "/suitntie/uploads/";
        $to = $_GET["to"];
        if($to == "addNewCategory"){
            $name = $_POST["new_category_name"];
            $status = $_POST["new_category_status"];
            $imageUrl = "/uploads/" . basename($_FILES["newCategoryImg"]["name"]);
            $result["file"] = fileUpload($target_dir, "image", $_FILES["newCategoryImg"]);
            if($result["file"]["status"] != "failed"){
                $result["is_saved"] = $program->save_new_program_category($name, $imageUrl, $status);
            }
            echo json_encode($result);
        }
        else if($to == "updateCategory"){
            $name = $_POST["edit_category_name"];
            $status = $_POST["edit_category_status"];
            $id = $_POST["edit_category_id"];
            if(!$_FILES["editCategoryImg"]["name"]){
                $result["is_saved"] = $program->update_program_category($name, "", $status, $id);
            }
            else{
                $imageUrl = "/uploads/" . basename($_FILES["editCategoryImg"]["name"]);
                $file = $target_dir . basename($_FILES["editCategoryImg"]["name"]);
                if(file_exists($file)){
                    unlink($file);
                }
                $result["file"] = fileUpload($target_dir, "image", $_FILES["editCategoryImg"]);
                if($result["file"]["status"] != "failed"){
                    $result["is_saved"] = $program->update_program_category($name, $imageUrl, $status, $id);
                }
            }
            echo json_encode($result);
        }
        else if($to == "updateProgram"){
            $name = $_POST["program_name"];
            $desc = $_POST["program_desc"];
            $status = $_POST["program_status"];
            $pc_id = $_POST["program_category_id"];
            $id = $_POST["program_id"];
            $is_updated = $program->update_program($name, $desc, $status, $pc_id, $id);
            echo json_encode($is_updated);
        }
        else if($to == "addNewProgram"){
            $name = $_POST["program_name"];
            $desc = $_POST["program_desc"];
            $status = $_POST["program_status"];
            $pc_id = $_POST["program_category_id"];
            $is_saved = $program->save_program($name, $desc, $status, $pc_id);
            echo json_encode($is_saved);
        }
    }
?>