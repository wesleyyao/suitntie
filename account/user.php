<?php
    session_start();
    if(isset($_SESSION["new_test_result"])){
        unset($_SESSION["new_test_result"]);
    }
?>
<!<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>用户中心</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/suitntie/asset/css/bootstrap.css">
        <link rel="stylesheet" href="/suitntie/asset/css/main.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="container" style="padding: 50px 0 25px 0">
            <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/navigation-bar.php"); ?>
            <br/>
            <div id="loadingDiv">
                <br/>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div id="accountCenter">
                <div class="row no-gutters">
                    <div class="col-lg-2 col-md-4 col-sm-2">
                        <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action account-nav active" id="userProfile">
                            <i class="fas fa-user"></i>
                            个人信息
                        </button>
                        <button type="button" class="list-group-item list-group-item-action account-nav" id="testResults">
                            <i class="fas fa-poll"></i>
                            测试结果
                        </button>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-8 col-sm-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="account-center-section" id="userProfileSection">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-3 col-sm-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <img src="/suitntie/asset/image/avatar.png" id="profileHeadImg" class="img-cell" style="width: 100%; height: auto;" alt="avatar"/>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#editProfileModal">修改资料</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-9 col-sm-8">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">昵称</label><br/>
                                                    <span class="text-muted" id="profileNickname">-</span>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">电话</label><br/>
                                                    <span class="text-muted" id="profileCellphone">-</span>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="h5">邮箱</label><br/>
                                                    <span class="text-muted" id="profileEmail">-</span>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">性别</label><br/>
                                                    <span class="text-muted" id="profileSex">-</span>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">城市</label><br/>
                                                    <span class="text-muted" id="profileCity">-</span>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">省份</label><br/>
                                                    <span class="text-muted" id="profileProvince">-</span>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <label class="h5">国家</label><br/>
                                                    <span class="text-muted" id="profileCountry">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="account-center-section" id="testResultsSection">
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="list-group list-group-horizontal-md" id="testCategory">
                                            </ul>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>日期</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="resultList">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="message"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="testResultModal" tabindex="-1" role="dialog" aria-labelledby="testResultTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="testResultTitle">测试结果</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="testResultBody">
                        <div id="testResultMessage"></div>
                        <div class="row">
                            <div class="col-12">
                                <div style="min-height: 200px;">
                                    <div class="container text-center result-title-cell" style="z-index: 2">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModaltitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="testResultTitle">修改资料</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="profileEditForm" style="padding: 0 20px">
                            <div class="form-row">
                                <div class="col col-lg-6 col-md-12">
                                    <label for="profileEditNickname">昵称：</label>
                                    <input type="text" class="form-control" id="profileEditNickname" maxlength="256" />
                                </div>
                                <div class="col col-lg-6 col-md-12">
                                    <label for="profileEditEmail"><span class="text-danger">*</span> 邮箱：</label>
                                    <input type="text" class="form-control required-input" id="profileEditEmail" maxlength="128" />
                                </div>
                            </div>
                            <br/>
                            <div class="form-row">
                                <div class="col col-lg-6 col-md-12">
                                    <label for="profileEditPhone"><span class="text-danger">*</span> 电话：</label>
                                    <input type="text" class="form-control required-input" id="profileEditPhone" maxlength="25" />
                                </div>
                                <div class="col col-lg-6 col-md-12">
                                    <label for="profileEditSex">性别：</label>
                                    <select class="form-control" id="profileEditSex">
                                        <option value="1">男</option>
                                        <option value="2">女</option>
                                    </select>
                                </div>
                            </div>
                            <br/>
                            <div class="form-row">
                                <div class="col col-lg-4 col-md-6">
                                    <label for="profileEditCity">城市：</label>
                                    <input type="text" class="form-control" id="profileEditCity" maxlength="256" />
                                </div>
                                <div class="col col-lg-4 col-md-6">
                                    <label for="profileEditProvince">省份：</label>
                                    <input type="text" class="form-control" id="profileEditProvince" maxlength="256" />
                                </div>
                                <div class="col col-lg-4 col-md-6">
                                    <label for="profileEditCountry">国家：</label>
                                    <input type="text" class="form-control" id="profileEditCountry" maxlength="256" />
                                </div>
                            </div>
                            <br/>
                            <div id="profileEditMessage"></div>
                            <div clas="form-row">
                                <div class="col-12 text-right">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">关闭</button>
                                    <button type="submit" class="btn btn-success">更新</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
        </div> 
        <?php if(isset($_SESSION["signup_message"])): ?>
            <div id="dimension-result-notification" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
                <div class="toast-header">
                    <img src="/asset/image/notice.png" class="rounded mr-2" alt="..." style="width: 25px; height: 25px">
                    <strong class="mr-auto">通知</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <i class="<?php echo $_SESSION["signup_message"]["type"] == "error" ? "fas fa-times text-danger" : "fas fa-check text-success" ?>"></i> 
                    <?php echo $_SESSION["signup_message"]["message"]; ?>
                </div>
            </div>
            <?php unset($_SESSION["signup_message"]); ?>
        <?php endif; ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
        <script src="/suitntie/asset/js/jquery-3.4.1.js"></script>
        <script src="https://kit.fontawesome.com/2383fb4f0d.js" crossorigin="anonymous"></script>
        <script src="/suitntie/asset/js/bootstrap.bundle.js"></script>
        <script src="/suitntie/asset/js/user.js"></script>
        <script src="/suitntie/asset/js/chart.js"></script>
        <script src="/suitntie/asset/js/account-center.js" charset="utf-8"></script>
    </body>
</html>
