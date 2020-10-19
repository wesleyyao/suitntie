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
    <link rel="stylesheet" href="<?php echo $global_prefix; ?>/asset/css/consult.css">
</head>

<body>
    <div>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="tutors" />
        <div class="consult-top-banner">
            <h1>导师库</h1>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <div id="consultantDiv">
                        <div id="consultantFilter" class="card border-light" style="background: #f9f9f9">
                            <div class="card-body">
                                <div class="input-group pb-3">
                                    <input type="text" class="form-control" placeholder="搜索导师" aria-label="搜索导师"
                                        id="filterInput" aria-describedby="filterBtn">
                                    <div class="input-group-append">
                                        <button class="btn primBtnSM" type="button" id="filterBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-lg-1 col-md-2 col-sm-3">
                                        <label><strong>地区</strong></label>
                                    </div>
                                    <div class="col-lg-11 col-md-10 col-sm-9">
                                        <div id="regionOptions"></div>
                                    </div>
                                </div>
                                <div class="row pb-2">
                                    <div class="col-lg-1 col-md-2 col-sm-3">
                                        <label><strong>专业</strong></label>
                                    </div>
                                    <div class="col-lg-11 col-md-10 col-sm-9">
                                        <div id="programOptions"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-3">
                                        <label><strong>学历</strong></label>
                                    </div>
                                    <div class="col-lg-11 col-md-10 col-sm-9">
                                        <div id="educationOptions"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="consultantList" class="pt-5">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-0">
                    <div id="adsDiv" class="card border-light" style="background: #fdf7f2">
                        <div class="card-body">
                            <h5>分享经验，传播智慧</h5>
                            <p>向世界分享您的知识，经验和见解, 别让您的梦想仅仅是梦想，勇敢地为您的梦想迈出第一步。</p>
                            <a href="#/" class="btn primBtn">成为导师</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
    <script type="module" src="<?php echo $global_prefix; ?>/asset/js/consult.js"></script>
</body>

</html>