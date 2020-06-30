<?php
    $appId = "wxcfeb0aba33ebba0c";
    $redirect_url = "http://www.suitntie.cn/suitntie/public/auth/wechat-login.php";
    $state = uniqid('', true);
    $_SESSION["wechat_state"] = $state;
?>
<div class="modal fade userLoginModal" id="userLoginModal" tabindex="-1" role="dialog" aria-labelledby="userLoginTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userLoginTitle">用户登录</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills row" id="loginTabs" role="tablist">
                            <li class="nav-item signup-options col-4 text-center"
                                style="padding:0 1px 0 15px;margin:0;">
                                <a class="nav-link btn-light active" id="phoneLogin-tab" data-toggle="tab"
                                    href="#phoneLogin" role="tab" aria-controls="phoneLogin"
                                    aria-selected="false">手机号</a>
                            </li>
                            <li class="nav-item signup-options col-4 text-center" style="padding:0 1px;margin:0;">
                                <a class="nav-link btn-light" id="emailLogin-tab" data-toggle="tab" href="#emailLogin"
                                    role="tab" aria-controls="emailLogin" aria-selected="false">邮箱</a>
                            </li>
                            <li class="nav-item signup-options col-4 text-center"
                                style="padding:0 15px 0 1px;margin:0;">
                                <a class="nav-link btn-light" id="wechatLogin-tab" data-toggle="tab" href="#wechatLogin"
                                    role="tab" aria-controls="wechatLogin" aria-selected="false">微信</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="loginTabs">
                            <div class="tab-pane fade show active" id="phoneLogin" role="tabpanel"
                                aria-labelledby="phoneLogin-tab">
                                <br />
                                <p>请输入您的11位国内手机号</p>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color:#f2f2f2;"
                                                    id="phoneNumberPrefix">+86</span>
                                            </div>
                                            <input type="text" id="loginPhone" class="form-control" placeholder="手机号"
                                                aria-label="手机号" aria-describedby="phoneNumberPrefix" maxlength="12">
                                            <div class="input-group-append">
                                                <button class="btn primBtnSM send-verify-code" id="sendLoginCode"
                                                    type="button">获取验证码</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-4 col-md-4 col-sm-5">
                                        <label><span class="text-danger">*</span> 验证码: </label>
                                        <input type="text" class="form-control" id="loginVerifyCode" maxlength="4" />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn ghostSecBtnSM close-signup-btn"
                                            data-dismiss="modal">关闭</button>
                                        <button type="button" class="btn primBtnSM login-button"
                                            id="loginByPhone">提交</button>
                                    </div>
                                </div>
                                <div id="loginByPhoneMessage"></div>
                            </div>
                            <div class="tab-pane fade" id="emailLogin" role="tabpanel" aria-labelledby="emailLogin-tab">
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <input type="text" id="loginEmail" name="login_email" placeholder="邮箱"
                                            class="form-control" maxlength="128" />
                                    </div>
                                </div>
                                <br />
                                <div class="row mt-1 mb-5">
                                    <div class="col-12">
                                        <input type="password" id="loginPassword" name="login_password" placeholder="密码"
                                            class="form-control" maxlength="20" />
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-12   text-right">
                                        <button type="button" class="btn ghostSecBtnSM mr-1"
                                            data-dismiss="modal">关闭</button>
                                        <button type="submit" class="btn primBtnSM login-button" id="loginByEmail"
                                            name="login_button">确认</button>
                                    </div>
                                </div>
                                <div id="loginByEmailMessage"></div>
                            </div>
                            <div class="tab-pane fade" id="wechatLogin" role="tabpanel"
                                aria-labelledby="wechatLogin-tab">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div id="login_container" class="text-center"></div>
                                        <div id="login_mobile_container" class="text-center row"
                                            style="padding: 50px 15px;">
                                            <a href="" id="login_wechat_mobile_link"
                                                class="btn primBtn col-12">点击此处授权</a>
                                            <p class="mt-1" style="margin:0 auto;"><small
                                                    style="font-weight:100 !important;">请确保在微信客户端中打开此网页</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-4">
                            <div class="col-12 text-right">
                                <span>尚未注册？<a href="#/" id="showSignupModal">请点击这里</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <ul class="nav nav-pills row" id="signupTabs" role="tablist">
                    <li class="nav-item signup-options col-6 text-center" style="padding:0 1px 0 15px;margin:0;">
                        <a class="nav-link btn-light active" id="phoneSignup-tab" data-toggle="tab" href="#phoneSignup"
                            role="tab" aria-controls="profile" aria-selected="false">微信注册 <sup><span
                                    class="badge login-badge">推荐</span></sup></a>
                    </li>
                    <li class="nav-item signup-options col-6 text-center" style="padding:0 15px 0 1px;margin:0;">
                        <a class="nav-link btn-light" id="emailSignup-tab" data-toggle="tab" href="#emailSignup"
                            role="tab" aria-controls="contact" aria-selected="false">邮箱注册</a>
                    </li>
                </ul>
                <div class="tab-content cellSignup" id="loginTabs">
                    <div class="tab-pane fade show active" id="phoneSignup" role="tabpanel"
                        aria-labelledby="phoneSignup-tab">
                        <div class="row">
                            <div class="col-12">
                                <div id="signup_container" class="text-center"></div>
                                <div id="signup_mobile_container" class="text-center row" style="padding: 50px 15px;">
                                    <a href="" id="signup_wechat_mobile_link" class="btn primBtn col-12">点击此处授权</a>
                                    <p class="mt-1" style="margin:0 auto;"><small
                                            style="font-weight:100 !important;">请确保在微信客户端中打开此网页</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="alert alert-primary" role="alert">
                                    <i class="fas fa-info-circle"></i> 如果您是海外用户，建议使用邮箱注册。
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade emailSignup" id="emailSignup" role="tabpanel"
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
                                <div class="input-group">
                                    <input type="text" id="signupEmail" class="form-control" placeholder=""
                                        aria-label="邮箱" maxlength="64">
                                    <div class="input-group-append">
                                        <button class="btn primBtnSM" id="sendEmailVerifyCode"
                                            type="button">获取验证码</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="signupEmailVerifyMessage"></div>
                        <div class="row mb-2">
                            <div class="col-lg-4 col-md-4 col-sm-5">
                                <label><span class="text-danger">*</span> 验证码: </label>
                                <input type="text" class="form-control" id="signupEmailCode" maxlength="4" />
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
                                <input type="password" id="signupPassword" maxlength="36"
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
                        <div id="signupByEmailMessage"></div>
                        <div class="row btnRow">
                            <div class="col-12 text-right">
                                <button type="button" class="btn ghostSecBtnSM close-signup-btn">关闭</button>
                                <button type="button" class="btn primBtnSM signup-button" id="signupByEmail">提交</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="alert alert-secondary" role="alert"
                                style="background-color:#f2f2f2;border:none;">
                                <span>已经注册？请点击<a href="#/" id="showLoginModal">此处</a>登录</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="userProfileCompleteModal" tabindex="-1" role="dialog"
    aria-labelledby="userProfileCompleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileCompleteModalTitle">完善用户资料</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-1 col-10">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> 为了确保我们的工作人员可以与您取得联系，请填写手机号。
                        </div>
                    </div>
                </div>
                <form id="userProfileCompleteForm">
                    <div class="row">
                        <div class="offset-1 col-10">
                            <p>请输入您的11位国内手机号</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-1 col-10">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="signupPhonePrefix">+86</span>
                                </div>
                                <input type="text" id="signupPhone" class="form-control" placeholder="手机号"
                                    aria-label="手机号" aria-describedby="signupPhonePrefix" maxlength="12">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary send-verify-code" id="sendSignupCode"
                                        type="button">获取验证码</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="offset-1 col-lg-4 col-md-4 col-sm-5">
                            <label><span class="text-danger">*</span> 验证码: </label>
                            <input type="text" class="form-control" id="signupVerifyCode" maxlength="4" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="offset-1 col-10 text-right">
                            <a class="btn ghostSecBtn" href="../../suitntie/account/user.php">回到个人中心</a>
                            <button type="submit" class="btn primBtn" id="CompleteProfile">提交</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-1 col-10">
                            <div id="profileCompleteMessage"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>