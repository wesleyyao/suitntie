<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/public/includes/initial.php");
    require_once("./page-common-functions.php");
    
    if(!isset($_GET["bId"])){
        header("Location: ../programs.php");
        exit;
    }
    
    $bId = $_GET["bId"];
    $id = $_GET["id"];
    $type = $_GET["type"];
    $program_id = $_GET["pid"];
    $title = $_GET["title"];
    $data;
    if($type == "edit"){
        $data = $program->fetch_program_data_by_id("recommend content", $id);
    }
    $formUrl = "../api/process-program.php?operate=$type&from=recommendation_content&id=$id&pid=$program_id&title=$title&bId=$bId";
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
                <form action="<?php echo $formUrl; ?>" enctype="multipart/form-data" method="post">
                    <div class="row">
                        <div class="col-12">
                            <h1><?php echo $type == "edit" ? "编辑" : "新增"; ?>自学推荐相关内容</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 标题</label>
                            <input type="text" maxlength="128" class="form-control"
                                value="<?php echo $type == "edit" ? $data["title"] : ""; ?>" name="content_title" />
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label>图标</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" name="content_image" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select name="content_status" class="form-control"
                                value="<?php echo $type == "edit" ? $data["status"] : "close"; ?>">
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 是否有链接？</label>
                            <select name="content_has_link" class="form-control"
                                value="<?php echo $type == "edit" ? $data["is_link"] : "no"; ?>">
                                <option value="yes">有</option>
                                <option value="no">无</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label>链接</label>
                            <input type="text" maxlength="128" class="form-control"
                                value="<?php echo $type == "edit" ? $data["url"] : ""; ?>" name="content_url" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-sm-12">
                            <label>作者</label>
                            <input type="text" maxlength="128" class="form-control"
                                value="<?php echo $type == "edit" ? $data["author"] : ""; ?>" name="content_author" />
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <label>豆瓣</label>
                            <input type="text" maxlength="24" class="form-control"
                                value="<?php echo $type == "edit" ? $data["douban"] : ""; ?>" name="content_douban" />
                        </div>
                    </div>
                    <input type="hidden" maxlength="256" class="form-control"
                        value="<?php echo $type == "edit" ? $data["image"] : ""; ?>" name="content_img_url" />
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <a class="btn btn-outline-secondary"
                                href="../program-details.php?program=<?php echo $title; ?>">返回</a>
                            <button class="btn btn-primary" type="submit" name="submitContent">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>