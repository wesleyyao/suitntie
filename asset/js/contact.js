import { generateMessage, prefix, formValidation } from './common.js';
$(document).ready(function () {
    $('#contactMessage').hide();
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;

    $('#contactForm').submit(function (e) {
        e.preventDefault();
        const email = $('#contactEmail').val();
        $('#contactMessage').html(loading);
        $('#contactMessage').fadeIn();
        if (!formValidation('.contact-required', '#contactMessage', email)) {
            return;
        }
        const phone = $('#contactPhone').val();
        const name = $('#contactName').val();
        const wechat = $('#contactWechat').val();
        const city = $('#contactCity').val();
        const school = $('#contactSchool').val();
        const content = $('#contactContent').val();
        $.post(`${prefix}/public/api/contact.php`, { email, phone, name, wechat, city, school, content }).done(function (data) {
            if (data) {
                $('#contactMessage').html(generateMessage('success', '提交成功！我们的工作人员会及时回复您。'));
            }
            else {
                $('#contactMessage').html(generateMessage('warning', '抱歉，提交未被处理。请稍后重试。'));
            }
            $('#contactMessage').delay(3000).fadeOut();
            $('.contact-required').each(function () {
                $(this).val('');
            });
        });
    });

    // function formValidation(className, message, email) {
    //     let isValid = true;
    //     $(className).each(function () {
    //         if (!$(this).val().replace(/\s/g, '')) {
    //             $(message).html(generateMessage('warning', '请填写所有必填项。'));
    //             $('#contactMessage').delay(3000).fadeOut();
    //             isValid = false;
    //             return;
    //         }
    //         else if (email && !validateEmailFormat(email)) {
    //             $(message).html(generateMessage('warning', '输入的邮箱格式错误。'));
    //             $('#contactMessage').delay(3000).fadeOut();
    //             isValid = false;
    //             return;
    //         }
    //         else {
    //             isValid = true;
    //             return;
    //         }
    //     });
    //     return isValid;
    // }

    // function validateEmailFormat(mail) {
    //     if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    //         return true;
    //     }
    //     return false;
    // }

    // function generateMessage(type, message) {
    //     return `
    //     <div class="row">
    //         <div class="col-12">
    //             <div class="alert alert-${type}">
    //                 <i class="${type == 'success' ? 'fas fa-check' : type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-times'}"></i> ${message}
    //             </div>
    //         </div>
    //     </div>`;
    // }
});