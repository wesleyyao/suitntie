import { prefix, useMessage, showFloatMessage, currentPage } from '../../../asset/js/common';

export function auth() {
    const signInMessage = `<span>未检测到登录用户，3秒后回到登录界面...</span>`

    $.get(`${prefix}/manager/api/account.php`).done(function(data) {
        if (!data) {
            $("#message").html(useMessage('danger', `无法连接服务器。`));
        }
        const result = JSON.parse(data);
        if (result.status !== 'success') {
            showFloatMessage('#message', useMessage(result.status, signInMessage), 3000);
            setInterval(function() {
                window.location.href = `${prefix}/manager/auth/login.html`;
            }, 3000);
        }
        else{
            $('#userInNav').html(`<a class="btn btn-warning" href="${prefix}/manager/api/signout.php"><i class="fas fa-sign-out-alt"></i> 登出</a>`);
        }
    });
}

export function renderNav() {
    $('#navDiv').load(`${prefix}/manager/global/header.html`, function() {
        $('#navHome').attr('href', `${prefix}/manager/index.html`);
        $('#navProgram').attr('href', `${prefix}/manager/modules/program/index.html`);
        $('#navCarousel').attr('href', `${prefix}/manager/modules/carousel/index.html`);
        $('#navConsultant').attr('href', `${prefix}/manager/modules/consultant/index.html`);
        if(currentPage.indexOf('program') > -1) {
            $('#navProgram').addClass('active');
        }
        else if(currentPage.indexOf('carousel') > -1){
            $('#navCarousel').addClass('active');
        }
        else if(currentPage.indexOf('consultant') > -1){
            $('#navConsultant').addClass('active');
        }
        else{
            $('#navHome').addClass('active');
        }
    });
}