import { generateMessage, prefix, formValidation } from './common.js';
$(document).ready(function () {
    $('#contactMessage').hide();
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    $('#contactFormDiv').load(`${prefix}/components/contact-form.html`);
    $(document).on('submit', '#contactForm', function (e) {
        e.preventDefault();
        const email = $('#contactEmail').val();
        $('#contactMessage').html(loading);
        //$('#contactMessage').fadeIn();
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
        $.post(`${prefix}/public/api/contact.php`, { email, phone, name, wechat, city, school, content }).done(function (data) {
            if (data) {
                $('#contactMessage').html(generateMessage('success', '提交成功！我们的工作人员会及时回复您。'));
            }
            else {
                $('#contactMessage').html(generateMessage('warning', '抱歉，提交未被处理。请稍后重试。'));
            }
            $('#contactMessage').fadeIn().delay(3000).fadeOut();
            $('.contact-required').each(function () {
                $(this).val('');
            });
        });
    });
});