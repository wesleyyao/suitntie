import { prefix, fetchAccount } from './common.js';

$(document).ready(function () {
    $('#homeTopSliderDiv').load(`${prefix}/components/home-top-slider.html`,
        function () {
            let topSlider = '';
            let topController = '';

            $.get(`${prefix}/public/api/home.php`).done(function (data) {
                const result = JSON.parse(data);
                if (result && Array.isArray(result) && result.length > 0) {
                    result.forEach(function (item, index) {
                        topSlider += `<div class="carousel-item ${index == 0 ? 'active' : ''}">
                    <img src="${prefix}${item.image}" class="d-block img-responsive" alt="home slider 1">
                    <div class="carousel-caption">
                        <h1>${item.title ? item.title : ''}</h1>
                        <p>${item.content ? item.content : ''}</p>
                        ${item.button ? `<a class="btn ghostBtn mt-3" href="${item.link ? prefix + item.link : '#/'}">${item.button}</a>` : ''}
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
    $('#homeTestimonialSliderDiv').load(`${prefix}/components/home-slider-testimonials.html`);
    $('#homeContactDiv').load(`${prefix}/components/contact-form.html`);
    fetchAccount();
});