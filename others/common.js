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

export const prefix = '/ceshi';