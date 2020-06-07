<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/includes/initial.php");
    require_once("./page-common-functions.php");
    
    $id = $_GET["id"];
    $type = $_GET["type"];
    $program_id = $_GET["pid"];
    $title = $_GET["title"];
    $data;
    if($type == "edit"){
        $data = $program->fetch_program_data_by_id("testimonial", $id);
    }
    $formUrl = "../api/process-program.php?operate=$type&from=testimonial&id=$id&pid=$program_id&title=$title";
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
                <form action="<?php echo $formUrl; ?>" method="post">
                    <div class="row">
                        <div class="col-12">
                            <h1><?php echo $type == "edit" ? "编辑" : "新增"; ?>客户反馈</h1>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 姓名</label>
                            <input type="text" maxlength="128" class="form-control" name="testimonial_name"
                                value="<?php echo $type == "edit" ? $data["name"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 内容</label>
                            <textarea type="text" maxlength="1500" class="form-control" rows="4"
                                name="testimonial_content"><?php echo $type == "edit" ? $data["feedback"] : ""; ?></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 学校</label>
                            <input type="text" maxlength="256" class="form-control" name="testimonial_school"
                                value="<?php echo $type == "edit" ? $data["school"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 专业</label>
                            <input type="text" maxlength="128" class="form-control" name="testimonial_program"
                                value="<?php echo $type == "edit" ? $data["program"] : ""; ?>" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 年级</label>
                            <input type="text" maxlength="64" class="form-control" name="testimonial_grade"
                                value="<?php echo $type == "edit" ? $data["grade"] : ""; ?>" />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select name="testimonial_status" class="form-control"
                                value="<?php echo $type == "edit" ? $data["status"] : "close"; ?>">
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <a class="btn btn-outline-secondary"
                                href="../program-details.php?program=<?php echo $title; ?>">返回</a>
                            <button class="btn btn-primary" type="submit" name="submitInfo">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>