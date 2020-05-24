<?php
    session_start();
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
    <div class="container">
        <div id="mainDiv">
            <?php require_once("./components/nav.php"); ?>
            <br />
            <div id="message"></div>
            <div id="programMain">
                <div class="row">
                    <div class="col-10">
                        <h1>专业分类</h1>
                    </div>
                    <div class="col-2 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#newCategoryModal">
                            <i class="fas fa-plus"></i> 添加</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>名称</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="programCategoryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10">
                        <h1>专业列表</h1>
                    </div>
                    <div class="col-2 text-right">
                        <button class="btn btn-primary" id="newProgramBtn">
                            <i class="fas fa-plus"></i> 添加</button>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-striped" id="programTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>概述</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="programsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="categoryDetailsModal" tabindex="-1" role="dialog" aria-labelledby="categoryDetailsTitle"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryDetailsTitle">专业类别</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="programTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>名称</th>
                                <th>简述</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="programTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("./components/newCategoryModal.php"); ?>
    <?php require_once("./components/editCategoryModal.php"); ?>
    <?php require_once("./components/programModal.php"); ?>
    <?php require_once("./components/login.php"); ?>
    <?php require_once("./components/script.php"); ?>
    <script src="/suitntie/manager/asset/js/program.js"></script>
</body>

</html>