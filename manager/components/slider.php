<?php
    require_once("../../utils/initial.php");
    require_once("../../public/includes/slider.php");
    $slider = new Slider();
    if(!isset($_SESSION["login_staff"])){
        header("Location: ./index.php");
        exit;
    }
    if(!isset($_GET["id"]) || !isset($_GET["type"])){
        header("Location: ../news.php");
        exit;
    }
    $id = $_GET["id"];
    $type = $_GET["type"];
    $data;
    if($type == "edit"){
        $data = $slider->fetch_slider_by_id($id);
    }
    $formUrl = "../api/process-news.php?operate=$type&id=$id";
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
            <?php if(isset($_SESSION["slider_message"])): ?>
            <div class="alert alert-<?php echo $_SESSION["slider_message"]["status"]; ?>" role="alert">
                <?php echo $_SESSION["slider_message"]["content"]; ?>
            </div>
            <?php unset($_SESSION["slider_message"]); ?>
            <?php endif; ?>
            <div id="programMain">
                <form action="<?php echo $formUrl; ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <h1><?php echo $type == "edit" ? "编辑" : "新增"; ?>走马灯</h1>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 标题</label>
                            <input type="text" maxlength="256" class="form-control" name="slider_title"
                                value="<?php echo $type == "edit" ? $data["title"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 图片</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" name="slider_img" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 内容</label>
                            <input type="text" maxlength="512" class="form-control" name="slider_content"
                                value="<?php echo $type == "edit" ? $data["content"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 链接</label>
                            <input type="text" maxlength="128" class="form-control" name="slider_link"
                                value="<?php echo $type == "edit" ? $data["link"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 按钮名字</label>
                            <input type="text" maxlength="64" class="form-control" name="slider_button"
                                value="<?php echo $type == "edit" ? $data["button"] : ""; ?>" />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 页面</label>
                            <select name="slider_type" class="form-control"
                                value="<?php echo $type == "edit" ? $data["type"] : "close"; ?>">
                                <option value="home">首页</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 排序</label>
                            <input type="number" maxlength="100" class="form-control"
                                value="<?php echo $type == "edit" ? $data["item_index"] : ""; ?>" name="slider_index" />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select name="slider_status" class="form-control"
                                value="<?php echo $type == "edit" ? $data["status"] : "close"; ?>">
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <a class="btn btn-outline-secondary" href="../news.php">返回</a>
                            <button class="btn btn-primary" type="submit" name="submitBtn">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>