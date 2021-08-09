<?php
require_once("../../utils/initial.php");
require_once("../../public/includes/consult.php");
$consultant = new Consult();
$result = array();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["operate"]) && isset($_GET["type"])) {
    $type = $_GET["type"];
    $operate = $_GET["operate"];
    $id = isset($_GET["id"]) ? $_GET["id"] : 0;

    if ($operate == "consultantTutor") {
        if (empty($_POST["tutor_nickname"]) || empty($_POST["tutor_status"]) || empty($_POST["tutor_education"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $tutor_name = empty($_POST["tutor_name"]) ? "" : $_POST["tutor_name"];
        $tutor_nickname = $_POST["tutor_nickname"];
        $tutor_status = $_POST["tutor_status"];
        $tutor_education = $_POST["tutor_education"];
        $tutor_intro = empty($_POST["tutor_intro"]) ? '' : $_POST["tutor_intro"];
        $imageUrl = empty($_FILES["tutor_img"]["name"]) ? "" : "/uploads/" . basename($_FILES["tutor_img"]["name"]);
        $target_dir = $_SERVER["DOCUMENT_ROOT"] . "$global_prefix/uploads/";
        $current_img = $_POST["current_tutor_img"];
        $isNotChangeImage = $_POST["not_change_image"];
        $uploadResult = array();
        $is_image_saved = true;
        if (!empty($imageUrl) && !$isNotChangeImage) {
            $file = $target_dir . ($isNotChangeImage ? $current_img : basename($_FILES["tutor_img"]["name"]));
            if (file_exists($file) && !$isNotChangeImage) {
                unlink($file);
            }
            if ($type == "new") {
                $uploadResult = fileUpload($target_dir, "image", $_FILES["tutor_img"]);
            } else {
                $skip = array();
                $skip["status"] = "success";
                $skip["message"] = "no change image";
                $uploadResult = $isNotChangeImage ? $skip : fileUpload($target_dir, "image", $_FILES["tutor_img"]);
            }
            if ($uploadResult["status"] == "failed") {
                $is_image_saved = false;
                $result["status"] = "warning";
                $result["content"] = $uploadResult['message'];
                echo json_encode($result);
                exit;
            }
        }
        else{
            $result["status"] = "warning";
            $result["content"] = "勾选了换图，但未检查到上传的图片。";
            echo json_encode($result);
            exit;
        }

        if ($type == "new") {
            $is_saved = $consultant->save_tutor($tutor_name, $tutor_nickname, $tutor_education, $tutor_intro, $imageUrl, $tutor_status);
            $result = generateMessage($is_saved, $type);
        } else {
            $imageUrl = $isNotChangeImage ? $current_img : $imageUrl;
            $is_updated = $consultant->update_tutor($tutor_name, $tutor_nickname, $tutor_education, $tutor_intro, $imageUrl, $tutor_status, $id);
            $result = generateMessage($is_updated, $type);
            $result["imgName"] = basename($_FILES["tutor_img"]["name"]) ? basename($_FILES["tutor_img"]["name"]) : '1';
        }
    } else if ($operate == "consultantSchool") {
        if (empty($_POST["school_name"]) || empty($_POST["school_region"]) || empty($_POST["school_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $school_name = $_POST["school_name"];
        $school_region = $_POST["school_region"];
        $school_status = $_POST["school_status"];

        if ($type == "new") {
            $is_saved = $consultant->save_school($school_name, $school_region, $school_status);
            $result = generateMessage($is_saved, $type);
        } else {
            $is_updated = $consultant->update_school($school_name, $school_region, $school_status, $id);
            $result = generateMessage($is_updated, $type);
        }
    } else if ($operate == "consultantRegion") {
        if (empty($_POST["region_name"]) || empty($_POST["region_index"]) || empty($_POST["region_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $region_name = $_POST["region_name"];
        $region_index = $_POST["region_index"];
        $region_status = $_POST["region_status"];

        if ($type == "new") {
            $is_saved = $consultant->save_region($region_name, $region_index, $region_status);
            $result = generateMessage($is_saved, $type);
        } else {
            $is_updated = $consultant->update_region($region_name, $region_index, $region_status, $id);
            $result = generateMessage($is_updated, $type);
        }
    } else if ($operate == "consultantProgram") {
        if (empty($_POST["program_id"]) || empty($_POST["school_id"]) || empty($_POST["education_name"]) || empty($_POST["program_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $program_id = $_POST["program_id"];
        $school_id = $_POST["school_id"];
        $education_name = $_POST["education_name"];
        $program_status = $_POST["program_status"];
        $scholarship = !empty($_POST["scholarship_name"]) ? $_POST["scholarship_name"] : "";
        if (!isset($_GET["tutorId"])) {
            $result["status"] = "warning";
            $result["content"] = "未找到该导师索引";
            echo json_encode($result);
            exit;
        }
        $tutor_id = $_GET["tutorId"];
        if ($type == "new") {
            $is_saved = $consultant->save_consultant_program($program_id, $school_id, $education_name, $program_status, $scholarship, $tutor_id);
            $result = generateMessage($is_saved, $type);
        } else {
            $is_updated = $consultant->update_consultant_program($program_id, $school_id, $education_name, $program_status, $scholarship, $id, $tutor_id);
            $result = generateMessage($is_updated, $type);
        }
    }
}
echo json_encode($result);
exit;

function generateMessage($is_true, $type)
{
    $result = array();
    if ($is_true == true) {
        $result["status"] = "success";
        $result["content"] = $type == "new" ? "新数据添加成功。" : "更新成功。";
    } else {
        $result["status"] = "warning";
        $result["content"] = $type == "new" ? "新数据添加失败。" : "更新失败。";
    }
    return $result;
}

function invalidForm()
{
    $result = array();
    $result["status"] = "warning";
    $result["content"] = "缺乏必须的字段。";
    return $result;
}
