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
        // $data["time"] = date("Y-m-d H:i:s");
        echo json_encode($data);
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["isUpdate"]) && $_POST["isUpdate"]){
            if(isset($_POST["nickName"]) && isset($_POST["sex"]) && isset($_POST["city"]) && isset($_POST["province"]) && isset($_POST["country"])){
                $nick_name = $_POST["nickName"];
                $sex = $_POST["sex"];
                $city = $_POST["city"];
                $province = $_POST["province"];
                $country = $_POST["country"];

                $is_update = $customer->update_user($nick_name, $sex, $city, $province, $country, $user_id);
                echo json_encode($is_update);
            }
            else{
                echo json_encode(false);
            }
        }
        else{
            echo json_encode(false);
        }
    }
?>