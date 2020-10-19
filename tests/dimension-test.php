<?php
    require_once("../utils/initial.php");
    require_once("../public/includes/dimension.php");
    $dimension = new Dimension();
    $available_dimensions = $dimension->fetchDimensions();
    $_SESSION["new_test"] = date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>适途咨询</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once("../theme/style.php"); ?>
</head>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class="test-main">
        <input type="hidden" id="hide_title" value="dimension-test" />
        <?php require_once("../theme/global-header.php"); ?>
        <?php if(isset($_SESSION["test_error_message"])): ?>
        <br />
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-times"></i>
                        <?php echo $_SESSION["test_error_message"]; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <?php unset($_SESSION["test_error_message"]); ?>
        <?php endif; ?>
        <div id="titleDiv">
            <div class="row mainTitle">
                <div class="col-12">
                    <h1 class="text-center" id="testTitle">Loading...</h1>
                </div>
            </div>
        </div>
        <div class="container mainContent">
            <div class="row testDesc">
                <div class="col-md-4 tri-block">
                    <div class="lightShadow tri-block-content">
                        <img src="../asset/image/test-page/timer.svg">
                        <p>测试长度大概为20分钟，回答没有对错好坏之分。</p>
                    </div>
                </div>
                <div class="col-md-4 tri-block">
                    <div class="lightShadow tri-block-content">
                        <img src="../asset/image/test-page/heart.svg">
                        <p>请选择真实的你的做法，而不要选择你认为哪样更好。</p>
                    </div>
                </div>
                <div class="col-md-4 tri-block">
                    <div class="lightShadow tri-block-content">
                        <img src="../asset/image/test-page/relax.svg">
                        <p>最大程度放松下来，不假思索地选择你的倾向。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-3">
                <div class="col-12">
                    <div id="message"></div>
                </div>
            </div>
        </div>
        <!-- <div class="alert alert-success" role="alert">
            <div class="spinner-border text-success" style="width: 1.3rem; height: 1.3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span>我们正在为您加载数据...</span>
        </div> -->
        <div id="testOrView">
            <div class="row">
                <div class="col-6">
                    <button class="btn primBtn btn-block" id="startTesting">开始测试</button>
                </div>
                <div class="col-6">
                    <a class="btn primBtn btn-block"
                        href="<?php echo $global_prefix; ?>/account/user.php?view=history">查看过往结果</a>
                </div>
            </div>
        </div>
        <div id="mainContentDiv" class="container">
            <div id="clockAndProgressBarDiv" class="test-div">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                        <span id="percentage" class="">0%</span>
                    </div>
                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8 progressContainer">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" id="progress" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                        <div id="clock"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-lg-2 col-lg-8 offset-md-1 col-md-10 col-sm-12 textHeading">
                    <h2 class="text-center" id="questionTypeTitle"></h2>
                    <div class="text-center result-div" id="resultSubtitle"></div>
                </div>
            </div>
            <br />
            <div id="mainQuestionDiv" class="test-div">
                <div class="row">
                    <div class="offset-lg-2 col-lg-8 offset-md-1 col-md-10 col-sm-12 text-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <br /><br />
                    </div>
                </div>
            </div>
            <div class="row test-div text-center">
                <div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 offset-sm-1 col-sm-10">
                    <button type="submit" class="btn primBtn" id="nextPage">下一页</button>
                </div>
            </div>
        </div>
        <div id="finishTestForm" class="container">
            <div class="needs-validation pt-5 pb-5" novalidate>
                <div class="form-row">
                    <div class="col-lg-3 offset-lg-3 col-md-4 offset-md-2 mb-3">
                        <label for="testUserName">姓名 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="testUserName" value="" required>
                    </div>
                    <div class="col-lg-3 col-md-4 mb-3">
                        <label for="testUserAge">年龄 <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="99" class="form-control" id="testUserAge" value="" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-2 offset-lg-3 col-md-4 offset-md-2">
                        <label>我目前: <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-2 offset-lg-3 col-md-4 offset-md-2 mb-3">
                        <div class="form-check">
                            <input class="purpose form-check-input" name="testUserStudyAboard" type="radio"
                                value="准备出国读书">
                            <label class="form-check-label">
                                准备出国读书
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-3">
                        <div class="form-check">
                            <input class="purpose form-check-input" name="testUserStudyAboard" type="radio"
                                value="准备在国内读书">
                            <label class="form-check-label">
                                准备在国内读书
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 offset-lg-0 offset-md-2 col-md-4 mb-3">
                        <div class="form-check">
                            <input class="purpose form-check-input" name="testUserStudyAboard" type="radio"
                                value="我就做着玩玩">
                            <label class="form-check-label">
                                我就做着玩玩
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row test-div text-center">
                    <div class="offset-lg-4 col-lg-4 offset-md-2 col-md-8 offset-sm-1 col-sm-10">
                        <button class="btn primBtn" id="submitTestFormBtn">查看结果</button>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("../theme/global-footer.php"); ?>
        <?php require_once("../public/component/auth-modals.php"); ?>
        <?php require_once("../theme/script.php"); ?>
        <script type="module" src="../asset/js/dimension-test.js"></script>
</body>

</html>