<?php
    session_start();
    if(isset($_SESSION["new_test"])){
        unset($_SESSION["new_test"]);
    }
    if(!isset($_SESSION["login_user"])){
        redirect("../account/user.php");
    }
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>适途教育</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/style.php"); ?>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="dimension-test-result-main">
        <input type="hidden" id="hide_title" value="dimension-test-result"/>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
            <div class="container" style="padding: 100px 0 25px 0">
                <br/>
                <div id="loadingDiv">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="mainResultDiv" class="result-div">
                    <div class="row resultHeader">
                        <div class="col-12">
                            <div style="min-height: 256px;">
                                <div class="container text-center result-title-cell" style="z-index: 2; width: 100%">
                                    <h1 class="display-4" id="resultTitle"></h1>
                                    <h2 id="resultDescription"></h2>
                                    <div class="lead" id="resultTags"></div>
                                </div>
                                <img id="characterImg" src="" />
                            </div>
                        </div>
                    </div>
                    <br/><br/>
                    <div class="row">
                        <div class="col-12">
                            <div id="resultCharts">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <canvas id="myChart0" height="300"></canvas>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <canvas id="myChart1" height="300"></canvas>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <canvas id="myChart2" height="300"></canvas>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <canvas id="myChart3" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12">
                            <h3>维度解释</h3>
                            <div id="dimensionAnalytics">

                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12">
                            <h3>基本分析</h3>
                            <div id="basicAnalytics">

                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12">
                            <h3>你的优势</h3>
                            <div id="advantageList">

                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12">
                            <h3>你的盲点</h3>
                            <div id="disadvantageList">

                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12">
                            <h3>专业和职业分析</h3>
                            <div id="programAndJobAnalytics">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <!-- <a href="#/" class="btn btn-info rounded"><i class="fab fa-weixin"></i> 分享到朋友圈</a> -->
                            <span class="text-danger">*提示： 每次测试结果都会保存在<a href="../account/user.php">个人中心</a>， 建议每半年做一次，看看自己有没有变化。</span>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-lg-3 col-sm-12">
                            <h5 class="text-center">没有看到心仪的专业？</h5>
                            <a class="btn btn-warning btn-block" href="../programs/index.php">探索更多专业</a>
                        </div>
                        <div class="offset-lg-6 col-lg-3 col-sm-12">
                            <h5 class="text-center">想找专业导师聊聊？</h5>
                            <a class="btn btn-warning btn-block" href="">一对一咨询</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="message"></div>
                    </div>
                </div>
                <div id="notification" class="notification-popup"></div>
            </div>
        </div>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-footer.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/script.php"); ?>
        <script src="/suitntie/asset/js/chart.js"></script>
        <script src="/suitntie/asset/js/result.js"></script>
    </body>
</html>
