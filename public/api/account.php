<?php
    require_once("../includes/initial.php");
    require_once("../includes/test-results.php");
    $results = new Results();
    $data = array();
    if(!isset($_SESSION["login_user"])){
        echo json_encode("no login");
        exit;
    }
    $user_id = $_SESSION["login_user"];
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $results->fetchAllResults($user_id);
        $customer->fetch_current_user($user_id);
        $data["results"] = $results->tests;
        $data["user"] = $customer;
        echo json_encode($data);
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["isUpdate"]) && $_POST["isUpdate"]){
            if(isset($_POST["nickName"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["sex"]) && isset($_POST["city"]) && isset($_POST["province"]) && isset($_POST["country"])){
                if(empty($_POST["email"]) || empty($_POST["phone"])){
                    echo json_encode(false);
                    exit;
                }
                $nick_name = $_POST["nickName"];
                $email = trim($_POST["email"]);
                $phone = trim($_POST["phone"]);
                $sex = $_POST["sex"];
                $city = $_POST["city"];
                $province = $_POST["province"];
                $country = $_POST["country"];
                $is_found_email = $customer->find_user_by_email($email);
                if(!$is_found_email){
                    echo json_encode(false);
                    exit; 
                }
                else if($is_found_email == "found" && $customer->id != $user_id){
                    echo json_encode("exist");
                    exit; 
                }
                $is_found_phone = $customer->find_user_by_phone($phone);
                if(!$is_found_phone){
                    echo json_encode(false);
                    exit; 
                }
                else if($is_found_phone && $customer->id != $user_id){
                    echo json_encode("exist");
                    exit; 
                }

                $is_update = $customer->update_user($nick_name, $email, $phone, $sex, $city, $province, $country, $user_id);
                echo json_encode($is_update);
            }
            else{
                echo json_encode(false);
            }
        }
        else if(isset($_POST["isSaveContact"]) && $_POST["isSaveContact"]){
            if(!isset($_POST["email"]) && !isset($_POST["phone"])){
                echo json_encode(false);
                exit;
            }
            $email = isset($_POST["email"]) ? $_POST["email"] : "";
            $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
            $is_save = $customer->save_contact($email, $phone, $user_id);
            echo json_encode($is_save);
        }
        else{
            echo json_encode(false);
        }
    }
?>