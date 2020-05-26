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
    <div class="container-fluid">
        <div id="mainDiv">
            <?php require_once("./components/nav.php"); ?>
            <br />
            <div id="message"></div>
            <div id="programMain">
                <div class="row">
                    <div class="col-10">
                        <h1>专业课程</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newCourseBtn" href="">
                            <i class="fas fa-plus"></i> 添加课程</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped" id="courseTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>介绍</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="courseTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10">
                        <h1>页面文字内容</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newInfoBtn" href="">
                            <i class="fas fa-plus"></i> 添加段落</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-striped" id="programInfoTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>内容</th>
                                    <th>类型</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="programInfoTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10">
                        <h1>客户反馈</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newTestimonialBtn" href="">
                            <i class="fas fa-plus"></i> 添加反馈</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-striped" id="testimonialTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>姓名</th>
                                    <th>内容</th>
                                    <th>学校</th>
                                    <th>专业</th>
                                    <th>年级</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="testimonialTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10">
                        <h1>自学推荐</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newRecommendBtn" href="">
                            <i class="fas fa-plus"></i> 添加推荐</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-striped" id="recommendTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>图片</th>
                                    <th>作者</th>
                                    <th>豆瓣</th>
                                    <th>链接</th>
                                    <th>公众号</th>
                                    <th>公开课</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="recommendTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-10">
                        <h1>子专业</h1>
                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" id="newChildProgramBtn" href="">
                            <i class="fas fa-plus"></i> 添加专业</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-striped" id="childProgramTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>内容</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="childProgramTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("./components/login.php"); ?>
    <?php require_once("./components/script.php"); ?>
    <script src="/suitntie/manager/asset/js/program-details.js"></script>
</body>

</html>