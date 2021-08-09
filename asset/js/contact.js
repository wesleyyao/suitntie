import { generateMessage, prefix, submittingMessage, formValidation } from './common.js';
$(document).ready(function () {
    $('#contactFormDiv').load(`${prefix}/components/contact-form.html`, function(){

    });
    $(document).on('submit', '#contactForm', function (e) {
        e.preventDefault();
        const email = $('#contactEmail').val();
        if (!formValidation('.contact-required', '#contactMessage', email)) {
            $('#contactMessage').html(generateMessage('warning', '确保填写所有必填项。'));
            $('#contactMessage').fadeIn().delay(3000).fadeOut();
            return;
        }
        const phone = $('#contactPhone').val();
        const name = $('#contactName').val();
        const wechat = $('#contactWechat').val();
        const city = $('#contactCity').val();
        const school = $('#contactSchool').val();
        const content = $('#contactContent').val();
        $('#contactMessage').html(submittingMessage);
        $('#contactMessage').fadeIn();
        $('#homeContactSubmit').prop('disabled', true);
        $.post(`${prefix}/public/api/contact.php?type=general`, { email, phone, name, wechat, city, school, content }).done(function (data) {
            if (!data) {
                $('#contactMessage').html(generateMessage('warning', '抱歉，无法连接服务器。请稍后重试。'));
            }
            const result = JSON.parse(data);
            if(result){
                $('#contactMessage').html(generateMessage('success', '提交成功！我们的工作人员会及时回复您。'));
            }
            else {
                $('#contactMessage').html(generateMessage('warning', '抱歉，提交未被处理。请稍后重试。'));
            }
            $('#contactMessage').show().delay(3000).fadeOut();
            $('.contact-input').each(function () {
                $(this).val('');
            });
            $('#homeContactSubmit').prop('disabled', true);
        });
    });
});