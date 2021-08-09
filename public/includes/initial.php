<?php
require_once("../../utils/initial.php");
require_once("test-data.php");
require_once("dimension.php");
require_once("customer.php");
require_once("dimension-result.php");
require_once("email.php");
require_once("office.php");
require_once("programs.php");
$test = new Test();
$dimension = new Dimension();
$customer = new Customer();
$dimension_result = new DimensionResult();
$email = new MailBox();
$office = new OfficeUser();
$program = new ProgramData();
