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
                        <h1>走马灯</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newCourseBtn" href="./components/slider.php?type=new&id=0">
                            <i class="fas fa-plus"></i> 添加</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped" id="sliderTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>图片</th>
                                    <th>标题</th>
                                    <th>内容</th>
                                    <th>链接</th>
                                    <th>排序</th>
                                    <th>页面</th>
                                    <th>按钮名字</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="sliderTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("./components/previewImageModal.php");?>
        <?php require_once("./components/login.php"); ?>
        <?php require_once("./components/script.php"); ?>
        <script type="module" src="/manager/asset/js/news.js"></script>
</body>

</html>