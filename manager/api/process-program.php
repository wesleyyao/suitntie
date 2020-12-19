<?php
require_once("../../public/includes/initial.php");
$result = array();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["operate"]) && isset($_GET["type"])) {
    $type = $_GET["type"];
    $from = $_GET["operate"];
    $id = $_GET["id"];
    $program_id = isset($_GET["pid"]) ? $_GET["pid"] : 0;

    if ($from == "programCourse") {
        if (empty($_POST["course_name"]) || empty($_POST["course_content"]) || empty($_POST["course_index"]) || empty($_POST["course_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $course_name = $_POST["course_name"];
        $course_content = $_POST["course_content"];
        $course_index = $_POST["course_index"];
        $course_status = $_POST["course_status"];
        if ($type == "new") {
            $is_saved = $program->save_program_course($program_id, $course_name, $course_content, $course_index, $course_status);
            $result = generateMessage($is_saved);
        } else {
            $is_updated = $program->update_program_course($id, $course_name, $course_content, $course_index, $course_status);
            $result = generateMessage($is_updated);
        }
    } else if ($from == "programInfo") {
        if (empty($_POST["info_type"]) || empty($_POST["info_content"]) || empty($_POST["info_index"]) || empty($_POST["info_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $info_type = $_POST["info_type"];
        $info_content = $_POST["info_content"];
        $info_index = $_POST["info_index"];
        $info_status = $_POST["info_status"];
        if ($type == "new") {
            $is_saved = $program->save_program_info($program_id, $info_content, $info_type, $info_index, $info_status);
            $result = generateMessage($is_saved);
        } else {
            $is_updated = $program->update_program_info($id, $info_content, $info_type, $info_index, $info_status);
            $result = generateMessage($is_updated);
        }
    } else if ($from == "programTestimonial") {
        if (empty($_POST["testimonial_name"]) || empty($_POST["testimonial_content"]) || empty($_POST["testimonial_school"]) || empty($_POST["testimonial_grade"]) || empty($_POST["testimonial_status"]) || empty($_POST["testimonial_program"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $testimonial_name = $_POST["testimonial_name"];
        $testimonial_content = $_POST["testimonial_content"];
        $testimonial_school = $_POST["testimonial_school"];
        $testimonial_grade = $_POST["testimonial_grade"];
        $testimonial_status = $_POST["testimonial_status"];
        $testimonial_program = $_POST["testimonial_program"];
        if ($type == "new") {
            $is_saved = $program->save_program_testimonial($program_id, $testimonial_name, $testimonial_content, $testimonial_school, $testimonial_program, $testimonial_grade, $testimonial_status);
            $result = generateMessage($is_saved);
        } else {
            $is_updated = $program->update_program_testimonial($id, $testimonial_name, $testimonial_content, $testimonial_school, $testimonial_program, $testimonial_grade, $testimonial_status);
            $result = generateMessage($is_updated);
        }
    } else if ($from == "programRecommend") {
        $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "$global_prefix/uploads/";
        if (empty($_POST["rec_title"]) || empty($_POST["rec_status"]) || empty($_POST["rec_index"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $rec_title = $_POST["rec_title"];
        $rec_index = $_POST["rec_index"];
        $rec_status = $_POST["rec_status"];
        $rec_image_url = $_POST["rec_image_url"];
        $imageUrl = "";
        if ($rec_title == "书") {
            $imageUrl = "/asset/image/resources/Resources_book.svg";
        } else if ($rec_title == "公众号") {
            $imageUrl = "/asset/image/resources/Resources_wechat.svg";
        } else {
            $imageUrl = "/asset/image/resources/Resources_onlineClass.svg";
        }
        // if($_FILES["rec_image"]["size"] != 0){
        //     $imageUrl = "/uploads/" . basename($_FILES["rec_image"]["name"]);
        //     $file = $target_dir . basename($_FILES["rec_image"]["name"]);
        //     if(file_exists($file)){
        //         unlink($file);
        //     }
        //     $result["file"] = fileUpload($target_dir, "image", $_FILES["rec_image"]);
        //     if($result["file"]["status"] == "failed"){
        //         $result["status"] = "warning";
        //         $result["content"] = "图片上传失败。";
        //         header("Location: $back_url");
        //         exit;
        //     }
        // }
        if ($type == "new") {
            $is_saved = $program->save_program_recommendation($program_id, $rec_title, $imageUrl, $rec_index, $rec_status);
            $result = generateMessage($is_saved);
        } else {
            if ($_FILES["content_image"]["size"] == 0) {
                //$imageUrl = $rec_image_url;
            }
            $is_updated = $program->update_program_recommendation($id, $rec_title, $imageUrl, $rec_index, $rec_status);
            $result = generateMessage($is_updated);
        }
    } else if ($from == "programRecommendContent") {
        $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "$global_prefix/uploads/";
        if (empty($_POST["content_title"]) || empty($_POST["content_status"]) || empty($_POST["content_has_link"]) || !isset($_GET["bid"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $content_title = $_POST["content_title"];
        $content_status = $_POST["content_status"];
        $content_has_link = $_POST["content_has_link"];
        $content_url = !empty($_POST["content_url"]) ? $_POST["content_url"] : "";
        $recommendation_id = $_GET["bid"];
        $content_image_url = $_POST["content_img_url"];
        $content_author = !empty($_POST["content_author"]) ? $_POST["content_author"] : "";
        $content_douban = !empty($_POST["content_douban"]) ? $_POST["content_douban"] : "";
        $imageUrl = "";

        if ($_FILES["content_image"]["size"] != 0) {
            $imageUrl = "/uploads/" . basename($_FILES["content_image"]["name"]);
            $file = $target_dir . basename($_FILES["content_image"]["name"]);
            if (file_exists($file)) {
                unlink($file);
            }
            $result["file"] = fileUpload($target_dir, "image", $_FILES["content_image"]);
            if ($result["file"]["status"] == "failed") {
                $result["status"] = "warning";
                $result["content"] = "图片上传失败。";
            }
        }
        if ($type == "new") {
            $is_saved = $program->save_program_recommendation_content($recommendation_id, $content_title, $content_has_link, $imageUrl, $content_url, $content_author, $content_douban, $content_status);
            $result = generateMessage($is_saved);
        } else {
            if ($_FILES["content_image"]["size"] == 0) {
                $imageUrl = $content_image_url;
            }
            $is_updated = $program->update_program_recommendation_content($id, $content_title, $content_has_link, $imageUrl, $content_url, $content_author, $content_douban, $content_status);
            $result = generateMessage($is_updated);
        }
    } else if ($from == "programChild") {
        if (empty($_POST["child_title"]) || empty($_POST["child_content"]) || empty($_POST["child_index"]) || empty($_POST["child_status"])) {
            $result = invalidForm();
            echo json_encode($result);
            exit;
        }
        $child_title = $_POST["child_title"];
        $child_content = $_POST["child_content"];
        $child_index = $_POST["child_index"];
        $child_status = $_POST["child_status"];
        if ($type == "new") {
            $is_saved = $program->save_program_children($program_id, $child_title, $child_content, $child_index, $child_status);
            $result = generateMessage($is_saved);
        } else {
            $is_updated = $program->update_program_children($id, $child_title, $child_content, $child_index, $child_status);
            $result = generateMessage($is_updated);
        }
    } else {
        $result["status"] = "warning";
        $result["content"] = "请求无法受理。";
        echo json_encode($result);
        exit;
    }
}
echo json_encode($result);
exit;

function generateMessage($is_true)
{
    $result = array();
    if ($is_true == true) {
        $result["status"] = "success";
        $result["content"] = "更新成功。";
    } else {
        $result["status"] = "warning";
        $result["content"] = "更新失败。";
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
