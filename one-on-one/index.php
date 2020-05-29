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
    <link rel="stylesheet" href="<?php echo $global_prefix; ?>/asset/css/one-on-one.css">
</head>

<body>
    <div class="one-on-one-page-main">
        <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/suitntie/theme/global-header.php"); ?>
        <input type="hidden" id="hide_title" value="oneOnone" />
        <div class="one-on-one-top-banner">
            <h1>一对一科普</h1>
        </div>
        <div class="container mt-5 mb-5 pt-5 pb-5">
            <div class="row one-on-one-reason">
                <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-12">
                    <h2 class="text-center mb-4">为什么要一对一？</h2>
                    <p>固然，互联网上关于不同专业的信息不计其数，从百度百科到知乎回答再到科普类博主。但是不同性格，兴趣，价值观，家庭背景都造就了独一无二的你。对于独立个体，教育不应该是灌输信息，也不因该是娱乐节目；教育是互动，是激励。而最具有针对性的方法就是一对一的沟通。
                    </p>
                </div>
            </div>
            <div class="one-on-one-section-1 mt-5">
                <div class="row mb-3">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-12 text-center">
                        <h2>我们的导师</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 col-sm-12">
                        <p>我们的每一位导师都经过精心挑选和审核，他们来自于全球顶级学府的各个专业，认同适途的文化和价值观，善于聆听并乐于分享。他们走过的路就是你可能面临的路。他们的人生经验和专业知识能给你从专业，选课，学校生活，工作各个方面提供指导和参考价值。我们希望，这样的精神能够薪火相传，也许未来的某一天，你也成为了学长学姐，走出了自己的道路，适途也期待着你能成为我们的新导师。
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Erik.png" alt="teacher" />
                        <h5 class="mt-3">Erik</h5>
                        <label>专业领域</label>
                        <p>机械工程，机器人</p>
                        <label>毕业院校</label>
                        <p>康奈尔大学，滑铁卢大学(全奖)</p>
                        <p class="mb-5 pl-2 pr-2">Erik学长本科毕业于康奈尔大学机械工程专业，研究生获得滑铁卢大学全奖并主攻机械工程机器人方向，曾在金矿公司研究怎么挖金矿，养了三只兔子。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Joyce.png" alt="teacher" />
                        <h5 class="mt-3">Joyce</h5>
                        <label>专业领域</label>
                        <p>数学，经济金融学</p>
                        <label>毕业院校</label>
                        <p>剑桥大学，伦敦大学学院（UCL)</p>
                        <p class="mb-5 pl-2 pr-2">Joyce学姐本科毕业于伦敦大学学院（UCL）数学专业，研究生毕业于剑桥大学经济金融学。目前在上海一家外企咨询公司就职。跳舞达人，爱吃茄子。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Cathy.png" alt="teacher" />
                        <h5 class="mt-3">Cathy</h5>
                        <label>专业领域</label>
                        <p>社会学</p>
                        <label>毕业院校</label>
                        <p>伦敦政经(LSE)</p>
                        <p class="mb-5 pl-2 pr-2">
                            Cathy学姐本科和研究生都毕业于伦敦政治经济学院（LSE）社会学专业，专注研究现代女权运动的推进与男权文化的兴起，喜欢洞察生活中细节且有趣的小事，并联想它与更广阔的社会结构的对接。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Sunny.png" alt="teacher" class="mb-auto" />
                        <h5 class="mt-3">Sunny</h5>
                        <label>专业领域</label>
                        <p>品牌管理</p>
                        <label>毕业院校</label>
                        <p>墨尔本大学，纽约大学</p>
                        <p class="mb-5 pl-2 pr-2">
                            Sunny学姐本科毕业于墨尔本大学商科，研究生毕业于纽约大学奢侈品市场和品牌管理专业。热爱时尚领域，曾就职于Vogue、Burberry、Hermes、Loewe。现在是位高定婚纱买手店主理人。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Ming.png" alt="teacher" />
                        <h5 class="mt-3">Ming</h5>
                        <label>专业领域</label>
                        <p>翻译，本地化</p>
                        <label>毕业院校</label>
                        <p>广东外语外贸大学，蒙特雷国际研究院</p>
                        <p class="mb-5 pl-2 pr-2">
                            Ming学姐是广东外语外贸大学翻译专业学士，蒙特雷国际研究院翻译本地化管理硕士，现就职谷歌，担任语言经理。梦想能够解放固化的中国教育现状。有着一百个梦想，一个个慢慢地去实现。</p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/小雨.png" alt="teacher" />
                        <h5 class="mt-3">小雨</h5>
                        <label>专业领域</label>
                        <p>人类学</p>
                        <label>毕业院校</label>
                        <p>剑桥大学，伦敦大学学院（UCL)</p>
                        <p class="mb-5 pl-2 pr-2">
                            小雨学姐是伦敦大学学院（UCL）材料与视觉文化（人类学院）的硕士，专注于青年文化与性别研究，曾在国内领先市场咨询公司实习，服务于可口可乐、腾讯等国内外知名品牌。</p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Violet.png" alt="teacher" />
                        <h5 class="mt-3">Violet</h5>
                        <label>专业领域</label>
                        <p>管理学</p>
                        <label>毕业院校</label>
                        <p>中国人民大学，伦敦政经（LSE）</p>
                        <p class="mb-5 pl-2 pr-2">
                            Violet学姐是人大经济学硕士和伦敦政治经济学院（LSE）管理学硕士，LSE全部科目Distinction，目前就职于国内Top互联网公司。不算是个成功的人，但是个走了很多弯路可以分享失败经验的咸鱼。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 card border-0 mt-5">
                    <div class="teacher-cell center-block text-center card-body d-flex flex-column pl-2 pr-2"
                        style="width:400px;max-width:100%;">
                        <img src="/suitntie/asset/image/photos/Maggie.png" alt="teacher" class="mb-auto" />
                        <h5 class="mt-3">Maggie</h5>
                        <label>专业领域</label>
                        <p>生物学，教育学</p>
                        <label>毕业院校</label>
                        <p>伦敦大学学院（UCL），墨尔本大学</p>
                        <p class="mb-5 pl-2 pr-2">
                            Maggie学姐本科毕业于伦敦大学学院（UCL）生物医学专业，现于墨尔本大学攻读教育系研究生，专精学生健康方向。目前忙于组织各种关注留学生心理健康的活动。热爱黑猫警长，毕竟万事都有教育意义。
                        </p>
                        <a href="#contactUs" class="btn primBtn mt-auto">请教学长姐</a>
                    </div>
                </div>
            </div>

            <div class="one-on-one-section-2 mt-5">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <h2>一对一能回答你哪些问题？</h2>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-10 offset-md-1 col-sm-12">
                        <p>不同的人格类型没有好坏之分，每个人都是独一无二的个体，都有其特别的优势和劣势，但问题关键在于如何认识这些优势和劣势。“取己之长，补己之短”，学会了这一点将会影响你的成败及你对未来专业和工作的喜好。
                        </p>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-10 offset-md-1 col-sm-12 mb-3">
                        <div style="margin-bottom: 10px; position:relative;">
                            <img src="../asset/image/pics/1-on-1/experiment.svg" width="30px" />
                            <h5 style="margin: 0; position:absolute; bottom:0; left:40px;">关于专业</h5>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1 col-sm-12">
                        <div>
                            <ul>
                                <li>在推荐的专业列表里我可能更适合哪个专业？</li>
                                <li>这些专业听起来很像，有什么区别？</li>
                                <li>这个专业有什么书和资料我可以提前了解的？</li>
                                <li>这个专业通常有哪些必修和选修课？</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <div>
                            <ul>
                                <li>假期的实习工作或者课外活动一般怎么找？</li>
                                <li>这个专业对应的行业是什么样的发展趋势？</li>
                                <li>有没有什么跨行业的机会？比如心理学应用在金融方面</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-10 offset-md-1 col-sm-12 mb-3">
                        <div style="margin-bottom: 10px; position:relative;">
                            <img src="../asset/image/pics/1-on-1/experiment.svg" width="30px" />
                            <h5 style="margin: 0; position:absolute; bottom:0; left:40px;">关于职业</h5>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1 col-sm-12">
                        <div>
                            <ul>
                                <li>这个专业毕业的学生都一般会进入什么行业和领域？</li>
                                <li>这个专业毕业后在不同的工作上起薪有多少？</li>
                                <li>这个专业，行业有哪些公司，他们都在做什么？</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <div>
                            <ul>
                                <li>我能从这个专业获得什么样的技能？</li>
                                <li>这个专业有哪些选课技巧？</li>
                                <li>这个专业如果学不下去有没有可能转到其他专业，比如哪些？</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1 col-sm-12 mb-3">
                        <div style="margin-bottom: 10px; position:relative;">
                            <img src="../asset/image/pics/1-on-1/experiment.svg" width="30px" />
                            <h5 style="margin: 0; position:absolute; bottom:0; left:40px;" class="align-text-bottom">
                                <span>关于大学生活</span></h5>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1 col-sm-12">
                        <div>
                            <ul>
                                <li>导师您当初为什么选择这个专业？</li>
                                <li>在XX大学XX专业就读是种怎样的体验？</li>
                                <li>大学里除了学好专业课，还需要锻炼哪些能力才能更好适应未来社会？</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <div>
                            <ul>
                                <li>在大学如何平衡GPA和课外活动经历？</li>
                                <li>如果真要进入这个专业，导师您还能给我其他什么忠告？</li>
                                <li>如果不推荐这个专业，有什么别的专业可能更适合我？</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-3">
                <div class="col-12 text-center">
                    <h2>适途一对一专业科普流程</h2>
                </div>
            </div>
            <div class="row fourSteps">
                <div class="col-lg-3 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 p-4">
                        <div class="card-body d-flex flex-column">
                            <div class="row">
                                <h3 class="card-title text-center col-lg-12 col-md-2 col-sm-2"><span
                                        class="numberCircle lightShadow">1</span></h3>
                                <p class="col-lg-12 col-md-10 col-sm-10 h-100 justify-content-center">
                                    学生通过适途评测获取独家的个人报告；主动探索了报告中最感兴趣的几个专业；并填写了个人信息表。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 p-4">
                        <div class="card-body d-flex flex-column">
                            <div class="row">
                                <h3 class="card-title text-center col-lg-12 col-md-2 col-sm-2"><span
                                        class="numberCircle lightShadow">2</span></h3>
                                <p class="col-lg-12 col-md-10 col-sm-10 h-100 justify-content-center">
                                    适途咨询师根据学生信息进一步与学生沟通，了解学生更多想法，并根据学生需求安排一个或多个一对一专业科普导师。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 p-4">
                        <div class="card-body d-flex flex-column">
                            <div class="row">
                                <h3 class="card-title text-center col-lg-12 col-md-2 col-sm-2"><span
                                        class="numberCircle lightShadow">3</span></h3>
                                <p class="col-lg-12 col-md-10 col-sm-10 h-100 justify-content-center">
                                    一对一专业科普导师收集适途咨询师给到的学生信息，根据约定的时间进行正式视频或音频沟通。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 d-flex align-items-stretch">
                    <div class="card border-0 p-4">
                        <div class="card-body d-flex flex-column">
                            <div class="row">
                                <h3 class="card-title text-center col-lg-12 col-md-2 col-sm-2"><span
                                        class="numberCircle lightShadow">4</span></h3>
                                <p class="col-lg-12 col-md-10 col-sm-10 h-100 justify-content-center">
                                    适途咨询师对学生进行回访，学生可以就与导师沟通的结果进行反馈。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-contact-form" id="contactUs">
            <h2 class="text-center pt-3">联系我们</h2>
            <?php require_once("../public/component/contact-us.php"); ?>
        </div>
    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/global-footer.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/public/component/auth-modals.php"); ?>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "$global_prefix/theme/script.php"); ?>
</body>

</html>