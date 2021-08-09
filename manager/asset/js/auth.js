import { prefix, loadingMessage, useMessage } from '../../../asset/js/common.js';

$(document).ready(function () {

    $('#loginForm').submit(function (e) {
        e.preventDefault();
        let isValid = true;
        $('#loginMessage').html(loadingMessage);
        $('.login-required').each(function () {
            if (!$(this).val().replace(/\s/g, '')) {
                isValid = false;
                $('#loginMessage').html(useMessage('warning', '请填写所有必填项。'));
            }
            else if (!validateEmailFormat($('#officeEmail').val())) {
                isValid = false;
                $('#loginMessage').html(useMessage('warning', '输入的邮箱格式错误。'));
            }
            else {
                isValid = true;
                return;
            }
        });

        if (isValid) {
            $('#submitLoginBtn').prop('disabled', true);
            const email = $('#officeEmail').val();
            const password = $('#officePwd').val();

            $.post(`${prefix}/manager/api/signIn.php`, { email, password }).done(function (data) {
                if (!data) {
                    $('#loginMessage').html(useMessage('warning', '无法连接服务器。'));
                }
                const result = JSON.parse(data);
                if (result.type === 'danger') {
                    $('#loginMessage').html(useMessage('warning', result.content));
                    $('#submitLoginBtn').prop('disabled', false);
                }
                else {
                    $('#loginMessage').html(useMessage('success', result.content));
                    setTimeout(function () {
                       window.location.href = `${prefix}/manager/index.html`;
                    }, 2000);
                }
            });
        }
    });

    function validateEmailFormat(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
        }
        return false;
    }
});