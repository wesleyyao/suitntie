$(document).ready(function(){
    const prefix = '/suitntie';
    $('#contactMessage').hide();
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    let topSlider = '';
    let topController = '';
    $.get(`${prefix}/public/api/home.php`).done(function(data){
        const result = JSON.parse(data); console.log(result)
        if(result && Array.isArray(result) && result.length > 0){
            result.forEach(function(item, index){
                topSlider += `<div class="carousel-item ${index == 0 ? 'active' : ''}">
                <img src="${prefix}${item.image}" class="d-block img-responsive" alt="home slider 1">
                <div class="carousel-caption">
                    <h1>${item.title ? item.title : ''}</h1>
                    <p>${item.content ? item.content : ''}</p>
                    ${item.button ? `<a class="btn ghostBtn mt-3" href="${item.link ? item.link : '#/'}">${item.button}</a>` : ''}
                </div>
            </div>`;
                if(result.length > 0){
                    topController += `<li data-target="#homeTopSlider" data-slide-to="${index}" class="${index == 0 ? 'active' : ''}"></li>`;
                }
            });
        }
        $('#homeTopSliderContent').html(topSlider);
        $('#homeSliderControllers').html(topController);
    });

    $('#contactForm').submit(function(e){
        e.preventDefault();
        const email = $('#contactEmail').val();
        $('#contactMessage').html(loading);
        $('#contactMessage').fadeIn();
        if(!formValidation('.contact-required', '#contactMessage', email)){
            return;
        }
        const phone = $('#contactPhone').val();
        const name = $('#contactName').val();
        const wechat = $('#contactWechat').val();
        const city = $('#contactCity').val();
        const school = $('#contactSchool').val();
        const content = $('#contactContent').val();
        $.post('/suitntie/public/api/contact.php', {email, phone, name, wechat, city, school, content}).done(function(data){
            if(data){
                $('#contactMessage').html(generateMessage('success', '提交成功！我们的工作人员会及时回复您。'));
            }
            else{
                $('#contactMessage').html(generateMessage('warning', '抱歉，提交未被处理。请稍后重试。'));
            }
            $('#contactMessage').delay(3000).fadeOut();
            $('.contact-required').each(function(){
                $(this).val('');
            });
        });
    });

    function formValidation(className, message, email){
        let isValid = true;
        $(className).each(function(){
            if (!$(this).val().replace(/\s/g, '')) {
                $(message).html(generateMessage('warning', '请填写所有必填项。'));
                $('#contactMessage').delay(3000).fadeOut();
                isValid = false;
                return;
            }
            else if(!validateEmailFormat(email)){
                $(message).html(generateMessage('warning', '输入的邮箱格式错误。'));
                $('#contactMessage').delay(3000).fadeOut();
                isValid = false;
                return;
            }
            else{
                isValid = true;
                return;
            }
        });
        return isValid;
    }

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
                <div class="alert alert-${type}">
                    <i class="${type == 'success' ? 'fas fa-check' : type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-times'}"></i> ${message}
                </div>
            </div>
        </div>`;
    }

    function timeoutHideModal(){
        setTimeout(function() {$('#contactMessage').fadeOut(); $('#profileEditMessage').html('');}, 2000);
    }
});