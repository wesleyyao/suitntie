<?php
    require_once("../../utils/initial.php");
    require_once("../includes/customer.php");
    $customer = new Customer();
    $appId = "wx25d424c51ed0650d";
    $secret = "b93d576736a7dabf70fef90294432cbd";
    $current_time = date("Y-m-d H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];
    $redirect_url = "/index.html";
    if(isset($_GET["code"]) && isset($_GET["state"])){
        $redirect_url = isset($_GET["redirect"]) ? $_GET["redirect"] : "/index.html";
        $code = $_GET["code"];
        $state = $_GET["state"];
        $url1 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$secret&code=$code&grant_type=authorization_code";
        $ch=curl_init();

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        curl_setopt($ch,CURLOPT_URL,$url1);

        $json1=curl_exec($ch);

        $arr1=json_decode($json1,1);
        //print_r($arr1);
        $access_token = $arr1["access_token"];
        $_SESSION["wechat_login_session"]["wechat_access_token"] = $arr1["access_token"];
        $_SESSION["wechat_login_session"]["wechat_refresh_token"] = $arr1["refresh_token"];
        $_SESSION["wechat_login_session"]["wechat_token_start_time"] = date("Y-M-D H:i:s");
        $_SESSION["wechat_login_session"]["wechat_unique_id"] = $arr1["unionid"];
        $_SESSION["wechat_login_session"]["wechat_open_id"] = $arr1["openid"];
        $open_id = $arr1["openid"];
        $url2 = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$open_id";
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        curl_setopt($ch,CURLOPT_URL,$url2);

        $json2=curl_exec($ch);

        curl_close($ch);

        $arr2=json_decode($json2,1);
        $union_id = $arr2["unionid"];
        $_SESSION["auth_message"]["unionid"] =$arr2["unionid"];
        // print_r($arr2);
        // exit;
        $found_user = $customer->find_user_by_uid($union_id);
        if($found_user){
            $_SESSION["login_user"] = $found_user;
        }
        else{
            $nickname = $arr2["nickname"];
            $sex = $arr2["sex"];
            $city = $arr2["city"];
            $province = $arr2["province"];
            $country = $arr2["country"];
            $headImg = $arr2["headimgurl"];
            $register_by = "";
            if(strpos($redirect_url, "programs/explore") > -1){
                $register_by = "专业";
            }
            if(strpos($redirect_url, "tests/dimension-test") > -1){
                $register_by = "测试";
            }
            $is_saved = $customer->save_wechat_uer($nickname, $sex, $city, $province, $country, $headImg, $union_id, $ip, $current_time, $register_by);
            if($is_saved){
                $_SESSION["login_user"] = $is_saved;
            }
            else{
                $_SESSION["auth_message"]["type"] = "error";
                $_SESSION["auth_message"]["message"] = "保存新用户数据未成功，请重新扫描二维码。";
            }
        }
        $redirect_url = isset($_GET["redirect"]) ? $_GET["redirect"] : "/index.html";
    }
    redirect($redirect_url);
    exit;
?>