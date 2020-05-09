<?php
    require_once("../utils/initial.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="<?php echo $global_prefix; ?>/asset/image/logo/logo.png">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/style.php"); ?>
    <link rel="stylesheet" href="<?php echo $global_prefix; ?>/asset/css/program.css">
</head>
<body>
    <div class="program-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="program"/>
        <div class="program-home-banner" style="margin-top: 80px;">
            <h1>专业探索</h1>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row mb-5" id="programMainDiv"></div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body one-on-one-promote">
                            <img class="program-one-on-one-img" src="<?php echo $global_prefix; ?>/asset/image/pics/programs/contact.png" />
                            <h2 class="card-title">一对一专业导师科普</h2>
                            <p>我们的每一位导师都经过精心挑选，他们来自于全球顶级学府的各个专业。他们走过的路就是你可能面临的路。他们的人生经验和专业知识能给你从专业，选课，学校生活，工作各个方面提供指导和参考价值。</p>
                            <a class="btn btn-warning" href="<?php echo $global_prefix; ?>/one-on-one.php">了解更多</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
    <script src="<?php echo $global_prefix; ?>/asset/js/program.js"></script>
</body>
</html>