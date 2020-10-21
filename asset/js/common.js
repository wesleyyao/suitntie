export function validateEmailFormat(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return true;
    }
    return false;
}

export function generateMessage(type, message) {
    return `
    <div class="row">
        <div class="col-12">
            <div class="alert alert-${type}">
                <i class="${type == 'success' ?
            'fas fa-check' : type === 'warning' ?
                'fas fa-exclamation-triangle' : type == 'info' ?
                    'fas fa-info-circle' : 'fas fa-times'}"></i> ${message}
            </div>
        </div>
    </div>`;
}

export const submittingMessage = `
<div class="row pb-2">
    <div class="col-12 text-center">
        <div class="spinner-border text-warning" style="width: 1.5rem; height: 1.5rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="h5">正在处理...</span>
    </div>
</div>`;

export const prefix = '/suitntie';

export function formValidation(className, message, email) {
    let isValid = true;
    $(className).each(function () {
        if (!$(this).val().replace(/\s/g, '')) {
            $(message).html(generateMessage('warning', '请填写所有必填项。'));
            $('#contactMessage').delay(3000).fadeOut();
            isValid = false;
            return;
        }
        else if (email && !validateEmailFormat(email)) {
            $(message).html(generateMessage('warning', '输入的邮箱格式错误。'));
            $('#contactMessage').delay(3000).fadeOut();
            isValid = false;
            return;
        }
        else {
            isValid = true;
            return;
        }
    });
    return isValid;
}