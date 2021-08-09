<?php
    ini_set('session.gc_maxlifetime', 36000);
    date_default_timezone_set("America/Toronto");
    session_start();
    require_once("database.php");
    $global_prefix = "/suitntie";
    function redirect($location){
        header("Location: {$location}");
        exit;
    }

    function fileUpload($dir, $file_type, $file){
        if($file["name"] == ""){
            $data["status"] = "empty";
            $data["message"] = "No image was uploaded";
            return $data;
        }
        $data = array();
        $target_dir = $dir;
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $message = "";
        $status = "";
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $message = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $message = "File is not an image.";
            $uploadOk = 0;
        }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($file["size"] > 5000000) {
            $message = "Sorry, your file is larger than 5MB.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($file_type == "image"){
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "svg" && $imageFileType != "webp") {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
        }      

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $status = "failed";
            // if everything is ok, try to upload file
        } 
        else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $status = "success";
                $message = "The file ". basename( $file["name"]). " has been uploaded.";
            } else {
                $status = "failed";
                $message = "Sorry, there was an error uploading your file.";
            }
        }
        $data["status"] = $status;
        $data["message"] = $message;
        return $data;
    }
?>