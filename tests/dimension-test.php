<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/utils/initial.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/dimension.php");
    $dimension = new Dimension();
    $available_dimensions = $dimension->fetchDimensions();
    $_SESSION["new_test"] = date("Y-m-d H:i:s");
?>
<!<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>适途16型人格测试</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/suitntie/asset/css/bootstrap.css">
        <link rel="stylesheet" href="/suitntie/asset/css/main.css">
        <script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="container" style="padding: 50px 0 25px 0">
            <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/navigation-bar.php"); ?>
            <?php if(isset($_SESSION["test_error_message"])): ?>
                <br/>
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
                <br/>
            <?php endif; ?>
            <div id="mainContentDiv">
                <div id="titleDiv">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-center text-warning" id="testTitle"></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body bg-light">
                                    <h2 class="text-center" id="questionTypeTitle"></h2>
                                    <div class="text-center result-div" id="resultSubtitle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/><br/>
                </div>
                <div id="clockAndProgressBarDiv" class="test-div">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div id="clock"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                            <span id="percentage" class="badge badge-primary">0%</span>
                        </div>
                        <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" id="progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div id="mainQuestionDiv" class="test-div">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
                <div class="row test-div">
                    <div class="offset-lg-4 col-lg-4 offset-md-4 col-md-4 col-sm-12">
                        <form id="testForm" action="process-dimension-result.php" method="POST">
                            <input type="hidden" id="testId" name="test_id" value="" />
                            <input type="hidden" id="resultCodes" name="result_codes" value="" />
                            <?php if(count($available_dimensions) > 0): ?>
                                <?php foreach($available_dimensions as $item): ?>
                                    <input type="hidden" id="dimension<?php echo $item["id"];?>" name="dimension_<?php echo $item["id"];?>" value="" />
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-warning btn-block" id="nextPage">下一页</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="message"></div>
                </div>
            </div>
        </div>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
        <script src="/suitntie/asset/js/jquery-3.4.1.js"></script>
        <script src="https://kit.fontawesome.com/2383fb4f0d.js" crossorigin="anonymous"></script>
        <script src="/suitntie/asset/js/bootstrap.bundle.js"></script>
        <script src="/suitntie/asset/js/chart.js"></script>
        <script src="/suitntie/asset/js/main.js"></script>
        <script src="/suitntie/asset/js/user.js"></script>
    </body>
</html>
