<?php
require_once("./utils/initial.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="<?php echo $global_prefix; ?>/asset/image/logo/logo.png">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/style.php"); ?>
    <link rel="stylesheet" href="<?php echo $global_prefix; ?>/asset/css/one-on-one.css">
</head>

<body>
    <div class="one-on-one-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="oneOnone" />
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="jumbotron">
                <h1 class="display-4">404</h1>
                <p class="lead">未找到您要访问的页面。</p>
            </div>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
</body>

</html>