<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public/includes/customer.php");
    $customer = new Customer();
    $customer->fetch_current_user(isset($_SESSION["login_user"]) ? $_SESSION["login_user"] : 0);
    $data["user"] = $customer;
    echo json_encode($data);   
?>