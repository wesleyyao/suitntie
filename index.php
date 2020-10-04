<?php
    require_once("./utils/initial.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="asset/image/logo/logo.png">
    <?php require_once("theme/style.php"); ?>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="asset/css/home.css">
</head>

<body>
    <div class="home-page-main">
        <?php require_once("theme/global-header.php"); ?>
        <?php require_once("theme/home-slider-top.php"); ?>
        <input type="hidden" id="hide_title" value="home" />
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row stepsTitle">
                <div class="col-12 text-center">
                    <h2>“我该如何选择未来的专业？”</h2>
                </div>
            </div>
            <div class="row threeSteps">
                <div class="col-lg-4 col-md-12 col-sm-12 d-flex align-items-stretch addBtmMargin-30">
                    <div class="card border-0 lightShadow">
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title">第一步</h3>
                            <h4 style="color: #F19F4D; padding-bottom: 10px">我是谁？</h4>
                            <p>适途评测基于著名的迈尔斯里格斯(MBTI)类型指标，为中国学生重新进行了深度定制，帮助学生从客观的角度更加科学地了解自己的性格偏好，从一定程度上消除对未来的迷茫感，为选择大学专业方向做好准备。
                            </p>
                            <a href="/tests/dimension-test.php" class="btn primBtn mt-auto">免费测试</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 d-flex align-items-stretch addBtmMargin-30">
                    <div class="card border-0 lightShadow">
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title">第二步</h3>
                            <h4 style="color: #F19F4D; padding-bottom: 10px">这些专业是啥？</h4>
                            <p>测试结果可以为个人提供有力参考，但更重要的是同学们可以积极主动地去探索这些陌生和未知的专业。适途为大家准备了各专业最新概况。在这里你可以针对感兴趣的专业做到心里有数。</p>
                            <a href="/programs/index.php" class="btn primBtn mt-auto">开始探索</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 lightShadow">
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title">第三步</h3>
                            <h4 style="color: #F19F4D; padding-bottom: 10px">能和学长姐聊聊吗？</h4>
                            <p>如需进一步了解感兴趣的专业，适途的专业导师库涵盖了全球顶级大学各类专业500+位优秀且有亲和力的学长学姐，乐于用自己的亲身经历，并结合同学的个人情况，提供一对一具有针对性的大学专业知识科普。
                            </p>
                            <a href="/one-on-one/index.php" class="btn primBtn mt-auto">了解更多</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("theme/home-slider-testimonials.php"); ?>
        <div class="home-programs-entrance text-center">
            <div class="container">
                <h2>超全专业库</h2>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                        <p>如需进一步了解感兴趣的专业，适途的专业导师库涵盖了全球顶级大学各类专业500+位优秀且有亲和力的学长学姐，乐于用自己的亲身经历，并结合同学的个人情况，提供一对一具有针对性的大学专业知识科普。
                        </p>
                    </div>
                </div>
                <a href="/programs/index.php" class="btn primBtn">浏览专业库</a>
            </div>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row mb-5 pb-5 home-one-on-one-entrance">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 on-on-one-intro">
                    <div class="text-center">
                        <h2>顶级院校学长学姐一对一解惑</h2>
                        <p>我们的每一位导师都经过精心挑选，他们来自全球顶级学府的各个专业。他们走过的路就是你可能面临的路。他们的人生经验和专业知识能给你从专业，选课，学校生活，工作各个方面提供指导和参考价值。
                        </p>
                        <a href="/one-on-one/index.php" class="btn primBtn">了解更多</a>
                    </div>
                </div>
                <div class="mentorContainer container">
                    <div class="hs__wrapper">
                        <div class="sliderControl">
                            <p>向左滑动</p>
                            <div class="arrow">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <ul class="hs text-center">
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Erik.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Erik</h5>
                                    <label>专业领域</label>
                                    <p>机械工程，机器人</p>
                                    <label>毕业院校</label>
                                    <p>康奈尔大学，滑铁卢大学(全奖)</p>
                                    <p>Erik学长本科毕业于康奈尔大学机械工程专业，研究生获得滑铁卢大学全奖并主攻机械工程机器人方向，曾在金矿公司研究怎么挖金矿，养了三只兔子。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Joyce.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Joyce</h5>
                                    <label>专业领域</label>
                                    <p>数学，经济金融学</p>
                                    <label>毕业院校</label>
                                    <p>剑桥大学，伦敦大学学院（UCL)</p>
                                    <p>Joyce学姐本科毕业于伦敦大学学院（UCL）数学专业，研究生毕业于剑桥大学经济金融学。目前在上海一家外企咨询公司就职。跳舞达人，爱吃茄子。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Cathy.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Cathy</h5>
                                    <label>专业领域</label>
                                    <p>社会学</p>
                                    <label>毕业院校</label>
                                    <p>伦敦政经(LSE)</p>
                                    <p>Cathy学姐本科和研究生都毕业于伦敦政治经济学院（LSE）社会学专业，专注研究现代女权运动的推进与男权文化的兴起，喜欢洞察生活中细节且有趣的小事，并联想它与更广阔的社会结构的对接。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Sunny.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Sunny</h5>
                                    <label>专业领域</label>
                                    <p>品牌管理</p>
                                    <label>毕业院校</label>
                                    <p>墨尔本大学，纽约大学</p>
                                    <p>Sunny学姐本科毕业于墨尔本大学商科，研究生毕业于纽约大学奢侈品市场和品牌管理专业。热爱时尚领域，曾就职于Vogue、Burberry、Hermes、Loewe。现在是位高定婚纱买手店主理人。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Ming.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Ming</h5>
                                    <label>专业领域</label>
                                    <p>翻译，本地化</p>
                                    <label>毕业院校</label>
                                    <p>广东外语外贸大学，蒙特雷国际研究院</p>
                                    <p>Ming学姐是广东外语外贸大学翻译专业学士，蒙特雷国际研究院翻译本地化管理硕士，现就职谷歌，担任语言经理。梦想能够解放固化的中国教育现状。有着一百个梦想，一个个慢慢地去实现。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/小雨.png" alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>小雨</h5>
                                    <label>专业领域</label>
                                    <p>人类学</p>
                                    <label>毕业院校</label>
                                    <p>东华大学，伦敦大学学院（UCL)</p>
                                    <p>
                                        小雨学姐是伦敦大学学院（UCL）材料与视觉文化（人类学院）的硕士，专注于青年文化与性别研究，曾在国内领先市场咨询公司实习，服务于可口可乐、腾讯等国内外知名品牌。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Violet.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Violet</h5>
                                    <label>专业领域</label>
                                    <p>管理学</p>
                                    <label>毕业院校</label>
                                    <p>中国人民大学，伦敦政经（LSE）</p>
                                    <p>Violet学姐是人大经济学硕士和伦敦政治经济学院（LSE）管理学硕士，LSE全部科目Distinction，目前就职于国内Top互联网公司。不算是个成功的人，但是个走了很多弯路可以分享失败经验的咸鱼。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow">
                                <div class="hs_item_image_wrapper">
                                    <img src="<?php echo $global_prefix; ?>/asset/image/photos/Maggie.png"
                                        alt="teacher" />
                                </div>
                                <div class="hs_item_description">
                                    <h5>Maggie</h5>
                                    <label>专业领域</label>
                                    <p>生物学，教育学</p>
                                    <label>毕业院校</label>
                                    <p>伦敦大学学院（UCL），墨尔本大学</p>
                                    <p>Maggie学姐本科毕业于伦敦大学学院（UCL）生物医学专业，现于墨尔本大学攻读教育系研究生，专精学生健康方向。目前忙于组织各种关注留学生心理健康的活动。热爱黑猫警长，毕竟万事都有教育意义。
                                    </p>
                                </div>
                                <div class="reachoutBtn">
                                    <a href="#/" class="btn primBtn mt-auto contact-btn">请教学长姐</a>
                                </div>
                            </li>
                            <li class="hs_item lightShadow lastSec">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-contact-form" id="contactUs">
            <h2 class="text-center pt-3">联系我们</h2>
            <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/contact-us.php"); ?>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
    <script type="module" src="<?php echo $global_prefix; ?>/asset/js/home.js"></script>
    <script src="<?php echo $global_prefix; ?>/asset/js/mentorSlider.js"></script>
</body>

</html>