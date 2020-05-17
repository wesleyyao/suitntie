<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理页面</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once("./components/style.php"); ?>
</head>

<body>
    <div class="container">
        <div id="mainDiv">
            <?php require_once("./components/nav.php"); ?>
            <br />
            <div id="message"></div>
            <div class="row">
                <div class="col-12">
                    <div class="card text-center">
                        <div class="card-body">
                            <a style="position: absolute; top: 10px; right: 15px;" href="./auth/office-logout.php"><i
                                    class="fas fa-sign-out-alt"></i> Logout</a>
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="card-title" id="staffName"></h5>
                                </div>
                            </div>
                            <h5 class="card-title" id="staffName"></h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>职务： <span id="staffTitle"></span></label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>部门： <span id="staffDepartment"></span></label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>权限： <span id="staffPermission"></span></label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>状态： <span id="staffStatus"></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <label>用户总数：</label>
                            <span class="h1" id="totalUser">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-12">
                    <table id="userTable" class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>昵称</th>
                                <th>邮箱</th>
                                <th>电话</th>
                                <th>性别</th>
                                <th>城市</th>
                                <th>省份</th>
                                <th>国家</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("./components/login.php"); ?>
    <div class="modal fade" id="testResultModal" tabindex="-1" role="dialog" aria-labelledby="testResultTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testResultTitle">测试历史</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="historyDiv">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="testResultDetailsModal" tabindex="-1" role="dialog"
        aria-labelledby="testResultDetailsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testResultDetailsModalTitle">测试结果</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="historyDetailsDiv">
                        <div class="row">
                            <div class="col-12">
                                <div style="min-height: 200px; background: #F19F4D">
                                    <div class="container text-center result-title-cell" style="z-index: 2">
                                        <h1 class="display-4" id="resultTitle"></h1>
                                        <h2 id="resultDescription"></h2>
                                        <div class="lead" id="resultTags"></div>
                                    </div>
                                    <!-- <img id="characterImg" src="" /> -->
                                </div>
                            </div>
                        </div>
                        <br /><br />
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
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <h3>维度解释</h3>
                                <div id="dimensionAnalytics">
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <h3>基本分析</h3>
                                <div id="basicAnalytics">
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <h3>你的优势</h3>
                                <div id="advantageList">
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <h3>你的盲点</h3>
                                <div id="disadvantageList">
                                </div>
                            </div>
                        </div>
                        <br />
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
    </div>
    <?php require_once("./components/script.php"); ?>
</body>

</html>