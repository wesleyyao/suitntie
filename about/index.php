<?php
    require_once("../utils/initial.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="<?php echo $global_prefix; ?>/asset/image/logo/logo.png">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/style.php"); ?>
    <link rel="stylesheet" href="<?php echo $global_prefix; ?>/asset/css/about.css">
</head>

<body>
    <div class="about-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="about" />
        <div class="about-top-banner">
            <h1>关于Suit n'Tie 适途咨询</h1>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row">
                <div class="col-lg-8 col-md-7 col-sm-12 p-3 pr-5 pl-5">
                    <h2 class="mb-5 text-center text-md-left text-lg-left">为什么叫Suit n'Tie 适途？</h2>
                    <p>Suit n'Tie (Suit and Tie的简称)
                        直译为西装与领带，是国际上正式场合的着装要求，也是长大成人的标志之一。同时Suit也有合适的意思，而Tie也有直接之意。正如我们希望的一样：让每个学生都能够用更科学的方法找到适合自己的专业方向；并帮助他们连接到理想专业的学长学姐，解决专业上的困惑，指引学术上的方向。因为我们坚信：适合自己的道路，才是最美的旅途。
                    </p>
                </div>
                <div class="col-lg-4 col-md-5 d-none d-md-block d-lg-block">
                    <img src="../asset/image/pics/about/about-img1.jpg" class="img-fluid pt-3" />
                </div>
            </div>
        </div>

        <div class="about-section-1">
            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-lg-4 col-md-4 d-none d-md-block d-lg-block">
                        <img src="../asset/image/pics/about/about-img2.jpg" class="img-fluid pt-3" />
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 contentBox">
                        <h2 class="mb-5 text-center text-md-left text-lg-left">我们的初衷</h2>
                        <p>我们是一群留学于各个国家的好朋友们，在用业余时间帮助了许多学弟学妹解决留学问题时，发现了一个很大的问题：为什么大多数学生花了大量的时间精力金钱在提高成绩，背景提升，申请学校时，却很少认真地琢磨下到底什么专业最适合自己？
                        </p>
                        <p>我们带着经验与思考，努力尝试着回答这个问题。我们认为比起学校的排名，找到适合自己的专业才是最重要的，因为它将会影响你的一生。而最能给你帮助的人，就是走过你这条路的人，你的学长学姐，你未来期望工作行业里的从业者们。
                        </p>
                        <p>于是我们成立了适途咨询，汇聚了一帮来自世界各国各校各个专业乐于分享的年轻人，专注解决学弟学妹选专业时产生的困惑。如果你认同我们的价值观，欢迎你有一天也能成为我们的导师，薪火相传。
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5 mb-5 pt-5 pb-5 about-section-2">
            <div class="row mb-3">
                <div class="col-12">
                    <h2 class="text-center">创始人介绍</h2>
                </div>
            </div>
            <div class="row mt-5 mb-5 partnerBlock">
                <div class="col-lg-3 col-sm-12 text-center partnerPhoto">
                    <img src="../asset/image/photos/Sherlock.png" class="img-fluid" />
                </div>
                <div class="col-lg-9 col-sm-12">
                    <h3 class="mb-3 text-lg-left text-center">方骞 Sherlock</h3>
                    <p>本科就读于加拿大劳瑞尔大学，主修经济与金融管理。在校期间担任大学的招生办招生大使。研究生就读于英国伦敦卡斯商学院。曾经任职于商务咨询公司服务于国外投行进行信用研究分析。曾联合创立的职前培训公司。曾任雅思老师，资深留学申请顾问。在工作和生活的起伏中，深深感到专业规划对留学生的重要性。创立适途平台，希望能让更多人少走不必要的弯路。
                    </p>
                </div>
            </div>
            <div class="row mt-5 mb-5 partnerBlock">
                <div class="col-lg-3 col-sm-12 text-center partnerPhoto">
                    <img src="../asset/image/photos/Tim.png" class="img-fluid" />
                </div>
                <div class="col-lg-9 col-sm-12">
                    <h3 class="mb-3 text-lg-left text-center">赵天祈 Tim</h3>
                    <p>本科就读于美国普渡大学，主修服务和旅游管理，辅修心理学。间隔年间在Uber做过市场分析；也曾加入过中国最大共享办公之一的创始团队；毕业后在国内最大的本地化咨询公司之一负责亚太区市场推广。在经历了留学路上的不断试错和自我探索后，希望通过创立适途这个平台，帮助迷茫中的学生找到适合自己的路。
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
</body>

</html>