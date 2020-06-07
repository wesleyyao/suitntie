<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");

    if(!isset($_SESSION["login_staff"])){
        header("Location: ./index.php");
        exit;
    }
    if(!isset($_GET["id"]) || !isset($_GET["type"]) || !isset($_GET["pid"]) || !isset($_GET["title"])){
        header("Location: ../programs.php");
        exit;
    }
    $id = $_GET["id"];
    $type = $_GET["type"];
    $program_id = $_GET["pid"];
    $title = $_GET["title"];
    $data;
    if($type == "edit"){
        $data = $program->fetch_program_data_by_id("book", $id);
    }
    $formUrl = "../api/process-program.php?operate=$type&from=recommendation&id=$id&pid=$program_id&title=$title";
?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理页面</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once("./style.php"); ?>
</head>

<body>
    <div class="container">
        <div id="mainDiv">
            <?php require_once("./nav.php"); ?>
            <br />
            <?php if(isset($_SESSION["program_message"])): ?>
            <div class="alert alert-<?php echo $_SESSION["program_message"]["status"]; ?>" role="alert">
                <?php echo $_SESSION["program_message"]["content"]; ?>
            </div>
            <?php unset($_SESSION["program_message"]); ?>
            <?php endif; ?>
            <div id="programMain">
                <form action="<?php echo $formUrl; ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <h1><?php echo $type == "edit" ? "编辑" : "新增"; ?>自学推荐</h1>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <ul>
                                    <li>标题会以分类的形式出现在页面上。（可以填写“书籍”，“公众号”，“视频”等）</li>
                                    <li>上传的图片会显示在标题左侧，成为其图标。</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 标题</label>
                            <input type="text" maxlength="256" class="form-control" name="rec_title"
                                value="<?php echo $type == "edit" ? $data["title"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label>图片</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" name="rec_image" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 排序</label>
                            <input type="number" max="999" min="1" class="form-control" name="rec_index"
                                value="<?php echo $type == "edit" ? $data["item_index"] : ""; ?>" />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select name="rec_status" class="form-control"
                                value="<?php echo $type == "edit" ? $data["status"] : "close"; ?>">
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" maxlength="256" class="form-control"
                        value="<?php echo $type == "edit" ? $data["image"] : ""; ?>" name="rec_image_url" />
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <a class="btn btn-outline-secondary"
                                href="../program-details.php?program=<?php echo $title; ?>">返回</a>
                            <button class="btn btn-primary" type="submit" name="submitInfo">提交</button>
                        </div>
                    </div>
                </form>
                <br />
                <br />
                <br />
            </div>
        </div>
    </div>
</body>

</html>