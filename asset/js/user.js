$(document).ready(function () {
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    $('#showSignupModal').click(function(){
        $('#userLoginModal').modal('hide');
        $('#userSignUpModal').modal('show');
    });
    $.get('/suitntie/public/api/account.php').done(function(data){
        const result = JSON.parse(data);
        if(result === 'no login'){
            $('#userLoginModal').modal('show');
            $('#mainContentDiv').fadeOut();
            message = generateMessage('warning', '未找到登录用户，请先<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录</a>');
            $('#message').html(message);
            return;
        }
        user = result.user ? result.user : undefined;
        const noLogin = `<a href="#/" data-toggle="modal" data-target="#userLoginModal">登录 | 注册</a>`;
        const isLogin = `<div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="userLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img id="loginUserAvatarInNav" src="" width="20" height="20" />
            </a>
            <div class="dropdown-menu" aria-labelledby="userLinks">
                <a class="dropdown-item" href="/suitntie/account/user.php">个人中心</a>
                <a class="dropdown-item" href="/suitntie/public/auth/user-logout.php">登出</a>
            </div>
        </div>`;
        $('#userInNav').html(user ? isLogin : noLogin);
        $('#loginUserAvatarInNav').attr('src', user.headImg ? user.headImg : '/suitntie/asset/image/avatar.png');
    });
    $('.submit-button').click(function () {
        const submitId = $(this).attr('id');
        const passwordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
        if (submitId === 'signupByEmail') {
            let isValid = true;
            $('.signup-required').each(function () {
                if (!$(this).val().replace(/\s/g, '')) {
                    const emptyErrorMessage = constructMessage('danger', '请填写所有必填项。');
                    $('#signupMessage').html(emptyErrorMessage);
                    isValid = false;
                    return;
                }
                else if(!validateEmailFormat($('#signupEmail').val())){
                    const invalidEmailMessage = constructMessage('danger', '输入的邮箱格式错误。');
                    $('#signupMessage').html(invalidEmailMessage);
                    isValid = false;
                    return;
                }
                else if(!$('#signupPassword').val().match(passwordFormat)){
                    const invalidPasswordMessage = constructMessage('danger', `密码不符合要求。请点击小图标<i class="fas fa-info-circle text-info"></i>查看密码格式，重新输入。`);
                    $('#signupMessage').html(invalidPasswordMessage);
                    isValid = false;
                    return;
                }
                else if($('#signupPassword').val() !== $('#signupPasswordConfirm').val()){
                    const invalidMatchPasswordMessage = constructMessage('danger', `两次输入的密码不一致。`);
                    $('#signupMessage').html(invalidMatchPasswordMessage);
                    isValid = false;
                    return;
                }
                else{
                    isValid = true;
                    return;
                }
            });
            if(isValid){
                const email = $('#signupEmail').val();
                const password = $('#signupPassword').val();
                $('#signupMessage').html(loading);
                $.post('/suitntie/public/api/signup.php', { submit_signup: 'yes', usr_email: email, usr_password: password}).done(function(data){
                    if(data){
                        let result = JSON.parse(data);
                        const message = constructMessage(result.type, result.content);
                        $('#signupMessage').html(message);
                    }
                });
            }
        }
    });

    $('#loginForm').submit(function(e){
        let isValid = true;
        $('.login-required').each(function(){
            if (!$(this).val().replace(/\s/g, '')) {
                const emptyErrorMessage = constructMessage('danger', '请填写所有必填项。');
                $('#loginMessage').html(emptyErrorMessage);
                isValid = false;
                return;
            }
            else if(!validateEmailFormat($('#loginEmail').val())){
                const invalidEmailMessage = constructMessage('danger', '输入的邮箱格式错误。');
                $('#loginMessage').html(invalidEmailMessage);
                isValid = false;
                return;
            }
            else{
                isValid = true;
                return;
            }
        });
        if(!isValid){
            e.preventDefault();
        }
    });

    function validateEmailFormat(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
        }
        return false;
    }

    function generateMessage(type, message){
        return  `
        <div class="row">
            <div class="col-12">
            <br/>
                <div class="alert alert-${type == 'success' ? 'success' : 'danger'}">
                    <i class="${type == 'success' ? 'fas fa-check' : 'fas fa-times'}"></i> ${message}
                </div>
            </div>
        </div>`;
    }
});