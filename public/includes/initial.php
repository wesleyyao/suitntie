<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once("test.php");
    require_once("dimension.php");
    require_once("customer.php");
    require_once("dimension-result.php");
    require_once("email.php");
    require_once("office.php");
    $test = new Test();
    $dimension = new Dimension();
    $customer = new Customer();
    $dimension_result = new DimensionResult();
    $email = new MailBox();
    $office = new OfficeUser();
?>