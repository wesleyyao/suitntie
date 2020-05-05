<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/customer.php");
    $customer = new Customer();
    $customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
    $data["user"] = $customer;
    echo json_encode($data);   
?>