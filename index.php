
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>适途咨询</title>
    <link rel="icon" href="/suitntie/asset/image/logo/logo.png">
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/style.php"); ?>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/suitntie/asset/css/home.css">
</head>
<body>
    <div class="home-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/home-slider-top.php"); ?>
        <input type="hidden" id="hide_title" value="home"/>
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
                            <h4>免费的适途独家评测</h4>
                            <p>适途评测基于著名的迈尔斯里格斯(MBTI)类型指标，为中国学生重新进行了深度定制，帮助学生从客观的角度更加科学地了解自己的性格偏好，从一定程度上消除对未来的迷茫感，为选择大学专业方向做好准备。</p>
                            <a href="/suitntie/tests/dimension-test.php" class="btn primBtn mt-auto">免费测试</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 d-flex align-items-stretch addBtmMargin-30">
                    <div class="card border-0 lightShadow">
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title">第二步</h3>
                            <h4>探索专业倾向</h4>
                            <p>测试结果可以为个人提供有力参考，但更重要的是同学们可以积极主动地去探索这些陌生和未知的专业。适途为大家准备了各专业最新概况。在这里你可以针对感兴趣的专业做到心里有数。</p>
                            <a href="" class="btn primBtn mt-auto">开始探索</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 lightShadow">
                        <div class="card-body text-center d-flex flex-column">
                            <h3 class="card-title">第三步</h3>
                            <h4>匹配一对一专业导师</h4>
                            <p>如需进一步了解感兴趣的专业，适途的专业导师库涵盖了全球顶级大学各类专业500+位优秀且有亲和力的学长学姐，乐于用自己的亲身经历，并结合同学的个人情况，提供一对一具有针对性的大学专业知识科普。</p>
                            <a href="" class="btn primBtn mt-auto">了解更多</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/home-slider-testimonials.php"); ?>
        <div class="home-programs-entrance text-center">
            <div class="container">
                <h2>超全专业库</h2>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                    <p>如需进一步了解感兴趣的专业，适途的专业导师库涵盖了全球顶级大学各类专业500+位优秀且有亲和力的学长学姐，乐于用自己的亲身经历，并结合同学的个人情况，提供一对一具有针对性的大学专业知识科普。</p>
                    </div>
                </div>
                <a href="" class="btn primBtn">浏览专业库</a>
            </div>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row mb-5 home-one-on-one-entrance ">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                    <div class="text-center">
                        <h2>一对一专业导师科普</h2>
                        <p>我们的每一位导师都经过精心挑选，他们来自全球顶级学府的各个专业。他们走过的路就是你可能面临的路。他们的人生经验和专业知识能给你从专业，选课，学校生活，工作各个方面提供指导和参考价值。</p>
                        <a href="" class="btn primBtn">了解更多</a>
                    </div>
                </div>
            </div>
            <div class="row instructor">
                <div class="col-12 text-center">
                    <h2>部分导师展示</h2>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-sm-12">
                    <div class="teacher-cell center-block" style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Erik.png" alt="teacher" class="mb-auto"/>
                        <div class="teacher-cell-info">
                            <h5>Erik</h5>
                            <label>专业领域：</label>
                            <p>机械工程，机器人</p>
                            <label>毕业院校:</label>
                            <p>康奈尔大学，滑铁卢大学</p>
                            <a class="animated-arrow" href="/suitntie/one-on-one/index.php">
                                <span class="the-arrow -left"><span class="shaft"></span></span>
                                <span class="main">
                                    <span class="text">请教学长姐</span>
                                    <span class="the-arrow -right">
                                        <span class="shaft"></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="teacher-cell center-block" style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Cathy.png" alt="teacher" class="mb-auto"/>
                        <div class="teacher-cell-info">
                            <h5>Cathy</h5>
                            <label>专业领域：</label>
                            <p>机械工程，机器人</p>
                            <label>毕业院校:</label>
                            <p>康奈尔大学，滑铁卢大学</p>
                            <a class="animated-arrow" href="/suitntie/one-on-one/index.php">
                                <span class="the-arrow -left"><span class="shaft"></span></span>
                                <span class="main">
                                    <span class="text">请教学长姐</span>
                                    <span class="the-arrow -right">
                                        <span class="shaft"></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-sm-12">
                    <div class="teacher-cell center-block" style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Joyce.png" alt="teacher" class="mb-auto"/>
                        <div class="teacher-cell-info">
                            <h5>Joyce</h5>
                            <label>专业领域：</label>
                            <p>机械工程，机器人</p>
                            <label>毕业院校:</label>
                            <p>康奈尔大学，滑铁卢大学</p>
                            <a class="animated-arrow" href="/suitntie/one-on-one/index.php">
                                <span class="the-arrow -left"><span class="shaft"></span></span>
                                <span class="main">
                                    <span class="text">请教学长姐</span>
                                    <span class="the-arrow -right">
                                        <span class="shaft"></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="teacher-cell center-block" style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Violet.png" alt="teacher" class="mb-auto"/>
                        <div class="teacher-cell-info">
                            <h5>Erik</h5>
                            <label>专业领域：</label>
                            <p>机械工程，机器人</p>
                            <label>毕业院校:</label>
                            <p>康奈尔大学，滑铁卢大学</p>
                            <a class="animated-arrow" href="/suitntie/one-on-one/index.php">
                                <span class="the-arrow -left"><span class="shaft"></span></span>
                                <span class="main">
                                    <span class="text">请教学长姐</span>
                                    <span class="the-arrow -right">
                                        <span class="shaft"></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-contact-form">
            <div class="container pt-5 pb-5">
                <h2 class="text-center pb-3">联系我们</h2>
                <form id="contactForm">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 姓名：</label>
                            <input type="text" maxlength="128" id="contactName" class="form-control contact-required" placeholder="姓名" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> E-Mail：</label>
                            <input type="email" maxlength="128" id="contactEmail" class="form-control contact-required" placeholder="E-Mail"/>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 手机号：</label>
                            <input type="text" maxlength="64" id="contactPhone" class="form-control contact-required" placeholder="手机号"/>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label> 微信号：</label>
                            <input type="text" maxlength="64" id="contactWechat" class="form-control" placeholder="微信号"/>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 所在城市：</label>
                            <input type="text" maxlength="128" id="contactCity" class="form-control contact-required" placeholder="所在城市"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 所在高校：</label>
                            <input type="text" maxlength="128" id="contactSchool" class="form-control contact-required" placeholder="所在高校"/>
                        </div>
                    </div>
                    
                    <div class="row pb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 提问：</label>
                            <textarea type="text" rows="5" maxlength="2500" id="contactContent" class="form-control"></textarea>
                        </div>
                    </div>
                    <div id="contactMessage"></div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn primBtn" id="homeContactSubmit">提交信息</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/script.php"); ?>
    <script src="/suitntie/asset/js/home.js"></script>
</body>
</html>