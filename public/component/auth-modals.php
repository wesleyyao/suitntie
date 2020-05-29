<?php
    $appId = "wxcfeb0aba33ebba0c";
    $redirect_url = "http://www.suitntie.cn/suitntie/public/auth/wechat-login.php";
    $state = uniqid('', true);
    $_SESSION["wechat_state"] = $state;
?>
<div class="modal fade" id="userLoginModal" tabindex="-1" role="dialog" aria-labelledby="userLoginTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userLoginTitle">用户登录</h5>
            </div>
            <form id="loginForm"
                action="/suitntie/public/auth/user-login.php?redirect=<?php echo $_SERVER["REQUEST_URI"]; ?>"
                method="post">
                <div class="modal-body">
                    <br />
                    <div class="row">
                        <div class="offset-1 col-10">
                            <input type="text" id="loginEmail" name="login_email" placeholder="&#xf0e0; 邮箱"
                                class="form-control login-required" maxlength="128" />
                        </div>
                    </div>
                    <br />
                    <div class="row mb-1">
                        <div class="offset-1 col-10">
                            <input type="password" id="loginPassword" name="login_password" placeholder="&#xf09c; 密码"
                                class="form-control login-required" maxlength="20" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-1 col-10">
                            <div class="row">
                                <div class="col-lg-5 col-sm-12">
                                    <a id="wechatLoginButton"
                                        href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo $appId; ?>&redirect_uri=<?php echo $redirect_url; ?>&response_type=code&scope=snsapi_login&state=<?php echo $state; ?>#wechat_redirect"><i
                                            class="fab fa-weixin text-success"></i> 微信登录</a>
                                </div>
                                <div class="col-lg-7 col-sm-12 text-right">
                                    <span>尚未注册？<a href="#/" id="showSignupModal">请点击这里</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loginMessage"></div>
                    <br />
                    <div class="row">
                        <div class="offset-1 col-10 text-right">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-success" id="loginSubmit"
                                name="login_button">确认</button>
                        </div>
                    </div>
                    <?php if(isset($_SESSION["auth_message"])): ?>
                    <br />
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="alert alert-<?php echo $_SESSION["auth_message"]["type"] == "error" ? "danger" : "success" ?> alert-dismissible fade show">
                                <i
                                    class="<?php echo $_SESSION["auth_message"]["type"] == "error" ? "fas fa-times" : "fas fa-check" ?>"></i>
                                <?php echo $_SESSION["auth_message"]["message"]; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php unset($_SESSION["auth_message"]); ?>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="userSignUpModal" tabindex="-1" role="dialog" aria-labelledby="userSignupTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSignupTitle">用户注册</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills" id="signupTabs" role="tablist">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" id="phoneSignup-tab" data-toggle="tab" href="#phoneSignup" role="tab"
                            aria-controls="profile" aria-selected="false">短信验证</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link active" id="emailSignup-tab" data-toggle="tab" href="#emailSignup" role="tab"
                            aria-controls="contact" aria-selected="false">邮箱</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!--     <div class="tab-pane fade show active" id="phoneSignup" role="tabpanel"
                        aria-labelledby="phoneSignup-tab">
                        <br />
                        <p>请选择区号，并输入您的手机号码。</p>
                        <div class="row">
                            <div class="col-3">
                                <select class="form-control ">
                                    <option value="1">+1</option>
                                    <option value="86">+86</option>
                                </select>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control" />
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-outline-secondary close-signup-btn">关闭</button>
                                <button type="button" class="btn btn-success submit-button"
                                    id="signupByPhone">提交</button>
                            </div>
                        </div>
                    </div> -->
                    <div class="tab-pane fade show active" id="emailSignup" role="tabpanel"
                        aria-labelledby="emailSignup-tab">
                        <br />
                        <div class="row mb-2">
                            <div class="col-12">
                                <label><span class="text-danger">*</span> 邮箱：<i class="fas fa-info-circle text-info"
                                        style="cursor: pointer" data-toggle="collapse" href="#signUpUserNameTip"
                                        role="button" aria-expanded="false"
                                        aria-controls="signUpUserNameTip"></i></label>
                                <div class="collapse" id="signUpUserNameTip">
                                    <div class="alert alert-secondary">
                                        请填写完整的邮箱地址，因为在注册提交后，我们会发送一封验证邮件到这个邮箱。
                                    </div>
                                </div>
                                <input type="email" class="form-control signup-required" id="signupEmail"
                                    maxlength="128" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <label><span class="text-danger">*</span> 密码：<i class="fas fa-info-circle text-info"
                                        style="cursor: pointer" data-toggle="collapse" href="#signUpPasswordTip"
                                        role="button" aria-expanded="false"
                                        aria-controls="signUpPasswordTip"></i></label>
                                <div class="collapse" id="signUpPasswordTip">
                                    <div class="alert alert-secondary">
                                        密码必须包含至少一个大写字母，一个小写字母和一个数字。长度不小于8
                                    </div>
                                </div>
                                <input type="password" id="signupPassword" maxlength="20"
                                    class="form-control signup-required" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <label><span class="text-danger">*</span> 确认密码：</label>
                                <input type="password" id="signupPasswordConfirm" maxlength="36"
                                    class="form-control signup-required" />
                            </div>
                        </div>
                        <div id="signupMessage"></div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-outline-secondary close-signup-btn">关闭</button>
                                <button type="button" class="btn btn-success submit-button"
                                    id="signupByEmail">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="userProfileCompleteModal" tabindex="-1" role="dialog"
    aria-labelledby="userProfileCompleteModalTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileCompleteModalTitle">完善用户资料</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="alert alert-light" role="alert">
                    为了确保我们的工作人员可以与您取得联系，请保存您的联系方式。
                </div>
                <form id="userProfileCompleteForm">
                    <div class="row mb-2" id="profileCompletePhoneCell">
                        <div class="offset-1 col-10">
                            <label><span class="text-danger">*</span> 手机：</label>
                            <input type="text" id="toFinishPhone" class="form-control profile-required"
                                maxlength="25" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-1 col-10">
                            <div id="profileCompleteMessage"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-1 col-10 text-right">
                            <a class="btn btn-outline-secondary" href="../../suitntie/account/user.php">回到个人中心</a>
                            <button type="submit" class="btn btn-success" id="CompleteProfile">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>