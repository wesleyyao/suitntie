<?php
    if(isset($_GET["title"])){
        $title = $_GET["title"];
    }
    else{
        redirect("index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="../asset/image/logo/logo.png">
    <?php require_once("../theme/style.php"); ?>
    <link rel="stylesheet" href="../asset/css/program.css">
</head>
<body>
    <div class="program-page-main">
        <?php require_once("../theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="program"/>
        <div class="program-home-banner">
            <h1><?php echo $title; ?></h1>
        </div>
        <div class="container mt-5 mb-2 pt-5 pb-5">
            <div id="programDetailsMainDiv">
                <div class="row mb-5">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="card border-0">
                            <div class="card-body">
                                <h2>专业简介</h2>
                                <div id="brief"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="card border-0 lightShadow relatedPrograms">
                            <div class="card-body">
                                <h5 class="card-title">类似专业</h5>
                                <div id="relatedPrograms"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="explore-section-2">
                    <div class="row">
                        <div class="col-12">
                            <h2>你适合学<?php echo $title; ?>吗？</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="ifSuitable"></div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col-12 preparation">
                            <div class="card border-0 bg-transparent">
                                <div class="card-body">
                                    <h5>你准备好了吗?</h5>
                                    <div id="ready"></div>
                                    <p>还没有做适途测试？</p>
                                    <a class="btn primBtn" href="../tests/dimension-test.php">点击测试</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("./components/slider-testimonials.php"); ?>
        <div class="container mt-5 mb-2">
            <div class="row mt-2 mb-3">
                <div class="col-12 text-center mt-5">
                    <h2>自学推荐</h2>
                </div>
            </div>
            <div id="recommend"></div>
            <br/><br/><hr><br/><br/>
            <div class="row mt-2 mb-5 courseList">
                <div class="col-lg-6 col-md-12">
                    <h3 class="text-center">相关课程</h3>
                    <div id="courses"></div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <h3 class="text-center">子专业列表</h3>
                    <div id="childPrograms"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="program-contact-form">
        <div class="container pt-5 pb-5">
        <h2 class="text-center pb-3">没有看到你有兴趣的专业？请留言联系我们</h2>
            <?php require_once("./components/contact-form.php"); ?>
        </div>
    </div>
    <?php require_once("../theme/global-footer.php"); ?>
    <?php require_once("../public/component/auth-modals.php"); ?>
    <?php require_once("../theme/script.php"); ?>
    <script src="../asset/js/program.js"></script>
    <script src="../asset/js/home.js"></script>
</body>
</html>