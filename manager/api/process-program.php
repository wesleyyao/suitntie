<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public/includes/initial.php");
    if(!isset($_SESSION["login_staff"])){
        header("Location: ../index.php");
        exit;
    }
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["operate"]) && isset($_GET["from"])){
        $type = $_GET["operate"];
        $from = $_GET["from"];
        $id = $_GET["id"];
        $program_id = $_GET["pid"];
        $title = $_GET["title"];
        $back_url = "";
        if($from == "course"){
            $back_url = "../components/program-course.php?type=$type&id=$id&pid=$program_id&title=$title";
            if(empty($_POST["course_name"]) || empty($_POST["course_content"]) || empty($_POST["course_index"]) || empty($_POST["course_status"])){
                invalidForm($back_url);
            }
            $course_name = $_POST["course_name"];
            $course_content = $_POST["course_content"];
            $course_index = $_POST["course_index"];
            $course_status = $_POST["course_status"];
            if($type == "new"){
                $is_saved = $program->save_program_course($program_id, $course_name, $course_content, $course_index, $course_status);
                generateMessage($is_saved);
                header("Location: $back_url");
                exit;
            }
            else{
                $is_updated = $program->update_program_course($id, $course_name, $course_content, $course_index, $course_status);
                generateMessage($is_updated);
                header("Location: $back_url");
                exit;
            }
        }
        else if($from == "info"){
            $back_url = "../components/program-info.php?type=$type&id=$id&pid=$program_id&title=$title";
            if(empty($_POST["info_type"]) || empty($_POST["info_content"]) || empty($_POST["info_index"]) || empty($_POST["info_status"])){
                invalidForm($back_url);
            }
            $info_type = $_POST["info_type"];
            $info_content = $_POST["info_content"];
            $info_index = $_POST["info_index"];
            $info_status = $_POST["info_status"];
            if($type == "new"){
                var_dump($_POST);
                $is_saved = $program->save_program_info($program_id, $info_content, $info_type, $info_index, $info_status);
                generateMessage($is_saved);
                header("Location: $back_url");
                exit;
            }
            else{
                $is_updated = $program->update_program_info($id, $info_content, $info_type, $info_index, $info_status);
                generateMessage($is_updated);
                header("Location: $back_url");
                exit;
            }
        }
        else if($from == "testimonial"){
            $back_url = "../components/program-testimonial.php?type=$type&id=$id&pid=$program_id&title=$title";
            if(empty($_POST["testimonial_name"]) || empty($_POST["testimonial_content"]) || empty($_POST["testimonial_school"]) || empty($_POST["testimonial_grade"]) || empty($_POST["testimonial_status"]) || empty($_POST["testimonial_program"])){
                invalidForm($back_url);
            }
            $testimonial_name = $_POST["testimonial_name"];
            $testimonial_content = $_POST["testimonial_content"];
            $testimonial_school = $_POST["testimonial_school"];
            $testimonial_grade = $_POST["testimonial_grade"];
            $testimonial_status = $_POST["testimonial_status"];
            $testimonial_program = $_POST["testimonial_program"];
            if($type == "new"){
                $is_saved = $program->save_program_testimonial($program_id, $testimonial_name, $testimonial_content, $testimonial_school, $testimonial_program, $testimonial_grade, $testimonial_status);
                generateMessage($is_saved);
                header("Location: $back_url");
                exit;
            }
            else{
                $is_updated = $program->update_program_testimonial($id, $testimonial_name, $testimonial_content, $testimonial_school, $testimonial_program, $testimonial_grade, $testimonial_status);
                generateMessage($is_updated);
                header("Location: $back_url");
                exit;
            }
        }
        else if($from == "recommendation"){
            $back_url = "../components/program-recommendation.php?type=$type&id=$id&pid=$program_id&title=$title";
            $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "/uploads/";
            if(empty($_POST["rec_title"]) || empty($_POST["rec_status"]) || empty($_POST["rec_index"])){
                invalidForm($back_url);
            }
            $rec_title = $_POST["rec_title"];
            $rec_index = $_POST["rec_index"];
            $rec_status = $_POST["rec_status"];
            $rec_image_url = $_POST["rec_image_url"];
            $imageUrl = "";
            if($rec_title == "书"){
                $imageUrl = "/asset/image/resources/Resources_book.svg";
            }
            else if($rec_title == "公众号"){
                $imageUrl = "/asset/image/resources/Resources_wechat.svg";
            }
            else{
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
            //         $_SESSION["program_message"]["status"] = "warning";
            //         $_SESSION["program_message"]["content"] = "图片上传失败。";
            //         header("Location: $back_url");
            //         exit;
            //     }
            // }
            if($type == "new"){
                $is_saved = $program->save_program_recommendation($program_id, $rec_title, $imageUrl, $rec_index, $rec_status);
                generateMessage($is_saved);
            }
            else{
                if($_FILES["content_image"]["size"] == 0){
                    //$imageUrl = $rec_image_url;
                }
                $is_updated = $program->update_program_recommendation($id, $rec_title, $imageUrl, $rec_index, $rec_status);
                generateMessage($is_updated);
            }
            header("Location: $back_url");
            exit;
        }
        else if($from == "recommendation_content"){
            $target_dir = $_SERVER["DOCUMENT_ROOT"] .  "/uploads/";
            if(empty($_POST["content_title"]) || empty($_POST["content_status"]) || empty($_POST["content_has_link"]) || !isset($_GET["bId"])){
                invalidForm($back_url);
            }
            $content_title = $_POST["content_title"];
            $content_status = $_POST["content_status"];
            $content_has_link = $_POST["content_has_link"];
            $content_url = !empty($_POST["content_url"]) ? $_POST["content_url"] : "";
            $recommendation_id = $_GET["bId"];
            $content_image_url = $_POST["content_img_url"];
            $content_author = !empty($_POST["content_author"]) ? $_POST["content_author"] : "";
            $content_douban = !empty($_POST["content_douban"]) ? $_POST["content_douban"] : "";
            $imageUrl = "";
            
            $back_url = "../components/program-recommendation-content.php?type=$type&id=$id&pid=$program_id&title=$title&bId=$recommendation_id";
       
            if($_FILES["content_image"]["size"] != 0){
                $imageUrl = "/uploads/" . basename($_FILES["content_image"]["name"]);
                $file = $target_dir . basename($_FILES["content_image"]["name"]);
                if(file_exists($file)){
                    unlink($file);
                }
                $result["file"] = fileUpload($target_dir, "image", $_FILES["content_image"]);
                if($result["file"]["status"] == "failed"){
                    $_SESSION["program_message"]["status"] = "warning";
                    $_SESSION["program_message"]["content"] = "图片上传失败。";
                    header("Location: $back_url");
                    exit;
                }
            }
            if($type == "new"){
                $is_saved = $program->save_program_recommendation_content($recommendation_id, $content_title, $content_has_link, $imageUrl, $content_url, $content_author, $content_douban, $content_status);
                generateMessage($is_saved);
                header("Location: $back_url");
                exit;
            }
            else{
                if($_FILES["content_image"]["size"] == 0){
                    $imageUrl = $content_image_url;
                }
                $is_updated = $program->update_program_recommendation_content($id, $content_title, $content_has_link, $imageUrl, $content_url, $content_author, $content_douban, $content_status);
                generateMessage($is_updated);
                header("Location: $back_url");
                exit;
            }
        }
        else if($from == "child"){
            $back_url = "../components/program-children.php?type=$type&id=$id&pid=$program_id&title=$title";
            if(empty($_POST["child_title"]) || empty($_POST["child_content"]) || empty($_POST["child_index"]) || empty($_POST["child_status"])){
                invalidForm($back_url);
            }
            $child_title = $_POST["child_title"];
            $child_content = $_POST["child_content"];
            $child_index = $_POST["child_index"];
            $child_status = $_POST["child_status"];
            if($type == "new"){
                $is_saved = $program->save_program_children($program_id, $child_title, $child_content, $child_index, $child_status);
                generateMessage($is_saved);
                header("Location: $back_url");
                exit;
            }
            else{
                $is_updated = $program->update_program_children($id, $child_title, $child_content, $child_index, $child_status);
                generateMessage($is_updated);
                header("Location: $back_url");
                exit;
            }
        }
    }

    function generateMessage($is_true){
        if($is_true == true){
            $_SESSION["program_message"]["status"] = "success";
            $_SESSION["program_message"]["content"] = "更新成功。";
        }
        else{
            $_SESSION["program_message"]["status"] = "warning";
            $_SESSION["program_message"]["content"] = "更新失败。";
        }
    }

    function invalidForm($back_url){
        $_SESSION["program_message"]["status"] = "warning";
        $_SESSION["program_message"]["content"] = "缺乏必须的字段。";
        header("Location: $back_url");
        exit;
    }
?>