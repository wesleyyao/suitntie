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
    <!--[if gt IE 8]><!-->
    <html class="no-js">
    <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>用户中心</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/style.php"); ?>
    </head>

    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="user-main">
            <input type="hidden" id="hide_title" value="user" />
            <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
            <div class="container mt-5" style="padding: 20px 0 100px 0">
                <div class="row">
                    <div class="col-12">
                        <div id="message"></div>
                    </div>
                </div>
                <div id="loadingDiv">
                    <br />
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-warning" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="accountCenter">
                    <div class="row no-gutters">
                        <div class="col-lg-2 col-md-4 col-sm-2">
                            <div class="list-group">
                                <button type="button"
                                    class="list-group-item list-group-item-action account-nav active rounded-0 btn"
                                    id="userProfile">
                                    <i class="fas fa-user"></i>
                                    个人信息
                                </button>
                                <button type="button"
                                    class="list-group-item list-group-item-action account-nav rounded-0 btn"
                                    id="testResults">
                                    <i class="fas fa-poll"></i>
                                    测试结果
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-10">
                            <div class="card rounded-0">
                                <div class="card-body">
                                    <div class="account-center-section" id="userProfileSection">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-4">
                                                <div class="row">
                                                    <div class="col-12 text-center mb-5">
                                                        <img src="/suitntie/asset/image/avatar.png" id="profileHeadImg"
                                                            class="img-cell" style="width: 100%; height: auto;"
                                                            alt="avatar" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-8">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">昵称</label><br />
                                                        <span class="text-muted" id="profileNickname">-</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">电话</label><br />
                                                        <span class="text-muted" id="profileCellphone">-</span>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="h5">邮箱</label><br />
                                                        <span class="text-muted" id="profileEmail">-</span>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">性别</label><br />
                                                        <span class="text-muted" id="profileSex">-</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">城市</label><br />
                                                        <span class="text-muted" id="profileCity">-</span>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">省份</label><br />
                                                        <span class="text-muted" id="profileProvince">-</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="h5">国家</label><br />
                                                        <span class="text-muted" id="profileCountry">-</span>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button class="btn ghostSecBtn btn-block mb-4"
                                                            data-toggle="modal"
                                                            data-target="#editProfileModal">修改资料</button>
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
                                        <br />
                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table">
                                                    <thead class="bgGrey">
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
            </div>
        </div>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/account/components/dimension-test-result-modal.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/account/components/profile-editor-modal.php"); ?>
        <?php if(isset($_SESSION["signup_message"])): ?>
        <div id="dimension-result-notification" role="alert" aria-live="assertive" aria-atomic="true" class="toast"
            data-autohide="false">
            <div class="toast-header">
                <img src="/asset/image/notice.png" class="rounded mr-2" alt="..." style="width: 25px; height: 25px">
                <strong class="mr-auto">通知</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <i
                    class="<?php echo $_SESSION["signup_message"]["type"] == "error" ? "fas fa-times text-danger" : "fas fa-check text-success" ?>"></i>
                <?php echo $_SESSION["signup_message"]["message"]; ?>
            </div>
        </div>
        <?php unset($_SESSION["signup_message"]); ?>
        <?php endif; ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-footer.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/script.php"); ?>
        <script src="/suitntie/asset/js/chart.js"></script>
        <script src="/suitntie/asset/js/account-center.js" charset="utf-8"></script>
    </body>

    </html>