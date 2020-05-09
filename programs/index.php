<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="/suitntie/asset/image/logo/logo.png">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/style.php"); ?>
    <link rel="stylesheet" href="/suitntie/asset/css/program.css">
</head>
<body>
    <div class="program-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="program"/>
        <div class="program-home-banner" style="margin-top: 80px;">
            <h1>专业探索</h1>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div id="programMainDiv"></div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/script.php"); ?>
    <script src="/suitntie/asset/js/program.js"></script>
</body>
</html>