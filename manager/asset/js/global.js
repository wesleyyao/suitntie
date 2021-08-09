import { prefix, useMessage, showFloatMessage, currentPage } from '../../../asset/js/common.js';

export function auth() {
    const signInMessage = `<span>未检测到登录用户，3秒后回到登录界面...</span>`;

    return new Promise((resolve, reject) => {
        $.get(`${prefix}/manager/api/account.php`).done(function (data) {
            if (!data) {
                $("#message").html(useMessage('danger', `无法连接服务器。`));
            }
            const result = JSON.parse(data);
            if (result && result.status) {
                if (result.status !== 'success') {
                    showFloatMessage('#message', useMessage(result.status, signInMessage), 3000);
                    setInterval(function () {
                        window.location.href = `${prefix}/manager/auth/login.html`;
                    }, 3000);
                }
                else {
                    $('#userInNav').html(`<a class="btn btn-warning" href="${prefix}/manager/api/signout.php"><i class="fas fa-sign-out-alt"></i> 登出</a>`);
                    resolve(result);
                }
            }
            else {
                reject("error occurs");
            }
            // if (result.status !== 'success') {
            //     resolve(result)
            //     showFloatMessage('#message', useMessage(result.status, signInMessage), 3000);
            //     return false;
            //     setInterval(function () {
            //         window.location.href = `${prefix}/manager/auth/login.html`;
            //     }, 3000);
            // }
            // else {
            //     $('#userInNav').html(`<a class="btn btn-warning" href="${prefix}/manager/api/signout.php"><i class="fas fa-sign-out-alt"></i> 登出</a>`);
            //     return true;
            // }
        })
    });
}

export function renderNav() {
    $('#navDiv').load(`${prefix}/manager/global/header.html`, function () {
        $('#navHome').attr('href', `${prefix}/manager/index.html`);
        $('#navProgram').attr('href', `${prefix}/manager/modules/program/index.html`);
        $('#navCarousel').attr('href', `${prefix}/manager/modules/carousel/index.html`);
        $('#navConsultant').attr('href', `${prefix}/manager/modules/consultant/index.html`);
        $('#navRanking').attr('href', `${prefix}/manager/modules/ranking/index.html`);
        if (currentPage.indexOf('program') > -1) {
            $('#navProgram').addClass('active');
        }
        else if (currentPage.indexOf('carousel') > -1) {
            $('#navCarousel').addClass('active');
        }
        else if (currentPage.indexOf('consultant') > -1) {
            $('#navConsultant').addClass('active');
        }
        else if (currentPage.indexOf('ranking') > -1) {
            $('#navRanking').addClass('active');
        }
        else {
            $('#navHome').addClass('active');
        }
    });
}