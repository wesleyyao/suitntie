$(document).ready(function () {
    const prefix = '/suitntie';
    let topSlider = '';
    let topController = '';
    $.get(`${prefix}/public/api/home.php`).done(function (data) {
        const result = JSON.parse(data); console.log(result)
        if (result && Array.isArray(result) && result.length > 0) {
            result.forEach(function (item, index) {
                topSlider += `<div class="carousel-item ${index == 0 ? 'active' : ''}">
                <img src="${prefix}${item.image}" class="d-block img-responsive" alt="home slider 1">
                <div class="carousel-caption">
                    <h1>${item.title ? item.title : ''}</h1>
                    <p>${item.content ? item.content : ''}</p>
                    ${item.button ? `<a class="btn ghostBtn mt-3" href="${item.link ? item.link : '#/'}">${item.button}</a>` : ''}
                </div>
            </div>`;
                if (result.length > 0) {
                    topController += `<li data-target="#homeTopSlider" data-slide-to="${index}" class="${index == 0 ? 'active' : ''}"></li>`;
                }
            });
        }
        $('#homeTopSliderContent').html(topSlider);
        $('#homeSliderControllers').html(topController);
    });
});