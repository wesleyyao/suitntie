<?php
    require_once("../utils/initial.php");
    if(!isset($_SESSION["login_staff"])){
        header("Location: ./index.php");
        exit;
    }
?>
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
    <div class="container-fluid">
        <div id="mainDiv">
            <?php require_once("./components/nav.php"); ?>
            <br />
            <div id="message"></div>
            <div id="programMain">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-consultant-tab" data-toggle="pill" href="#pills-consultant"
                            role="tab" aria-controls="pills-consultant" aria-selected="true">导师</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-school-tab" data-toggle="pill" href="#pills-school" role="tab"
                            aria-controls="pills-school" aria-selected="false">学校</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-region-tab" data-toggle="pill" href="#pills-region" role="tab"
                            aria-controls="pills-region" aria-selected="false">地区</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-consultant" role="tabpanel"
                        aria-labelledby="pills-consultant-tab">
                        <div class="row">
                            <div class="col-10">
                                <h1>导师列表</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button class="btn btn-primary" id="newConsultant">
                                    <i class="fas fa-plus"></i> 添加</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped" id="consultantTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>全名</th>
                                            <th>昵称</th>
                                            <th>最高学历</th>
                                            <th>介绍</th>
                                            <th>头像</th>
                                            <th>状态</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="consultantTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-school" role="tabpanel" aria-labelledby="pills-school-tab">
                        <div class="row mt-4">
                            <div class="col-10">
                                <h1>学校列表</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button class="btn btn-primary" id="newSchool">
                                    <i class="fas fa-plus"></i> 添加</button>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <table class="table table-striped" id="schoolTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>名称</th>
                                            <th>地区</th>
                                            <th>状态</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="schoolTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("./components/consultant/newDataModal.php"); ?>
    <?php require_once("./components/script.php"); ?>
    <script src="<?php echo $global_prefix; ?>/manager/asset/js/consultant.js"></script>
</body>

</html>