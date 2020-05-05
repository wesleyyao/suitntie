<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/customer.php");
    $customer = new Customer();
    $appId = "wxcfeb0aba33ebba0c";
    $secret = "da10d4ea0a4d2a36843b81044642759e";
    if(isset($_GET["code"]) && isset($_GET["state"])){
        $code = $_GET["code"];
        $state = $_GET["state"];

        $url1 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$secret&code=$code&grant_type=authorization_code";
        $ch=curl_init();

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        curl_setopt($ch,CURLOPT_URL,$url1);

        $json1=curl_exec($ch);

        $arr1=json_decode($json1,1);

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
        //print_r($arr2);
        $found_user = $customer->find_user_by_uid($arr1["unionid"]);
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
            $is_saved = $customer->save_wechat_uer($nickname, $sex, $city, $province, $country, $headImg, $arr1["unionid"]);
            //echo $is_saved;
            if($is_saved){
                $_SESSION["login_user"] = $is_saved;
            }
            else{
                $_SESSION["auth_message"]["type"] = "error";
                $_SESSION["auth_message"]["message"] = "保存新用户数据未成功，请重新扫描二维码。";
            }
        }
        header("Location: /suitntie/user.php");
        exit;
    }        
?>