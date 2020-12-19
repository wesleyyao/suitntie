import { prefix, showFloatMessage, useMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global';

$(document).ready(function(){
    $('#message').hide();
    $('#message').css('z-index', 1060);
    renderNav();
    auth();

    let homeSliders = [];
    let currentId = 0;
    
    $('#carouselFormDiv').load(`${prefix}/manager/modules/carousel/components/slider.html`);
    fetchCarouselData();

    $(document).on('click', '.previewImgBtn', function(){
        const path = prefix + $(this).attr('id').replace('check_', '');
        $('#previewImage').prop('src', path);
        $('#carouselImageModal').modal('show');
    });

    $('#newSliderBtn').click(function(){
        $('.slider-field').each(function () {
            $(this).val('');
        });
        currentId = 0;

        $('#currentSliderImg').val("");
        $('#carouselModal').modal('show');
    });

    $(document).on('click', '.slider-edit-btn', function(){
        const id = $(this).attr('id').replace('slider', '');
        currentId = id;
        const foundData = homeSliders.find(item => item.id == id);
        if(!foundData){
            showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
            return;
        }
        $('#sliderTitle').val(foundData.title);
        $('#sliderContent').val(foundData.content);
        $('#sliderLink').val(foundData.link);
        $('#sliderButton').val(foundData.button);
        $('#sliderType').val(foundData.type);
        $('#sliderStatus').val(foundData.status);
        $('#sliderIndex').val(foundData.item_index);
        $('#currentSliderImg').val(foundData.image);
        $('#carouselModal').modal('show');
    });

    $(document).on('submit', '#sliderForm', function(e){
        e.preventDefault();
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        $.ajax({
            url: `${prefix}/manager/api/process-carousel.php?type=${currentId == 0 ? 'new' : 'update'}&id=${currentId}`,
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data) {
                    const result = JSON.parse(data);
                    $('#programDetailsModal').modal('hide');
                    showFloatMessage('#message', useMessage(result.status, result.content), 3000);
                    $('#sliderTable').DataTable().destroy();
                    fetchCarouselData();
                }
                else {
                    showFloatMessage('#message', useMessage('warning', '未保存成功，请重试'), 3000);
                }
            },
            error: function (e) {
                console.log("ERROR : ", e);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function fetchCarouselData(){
        $.get(`${prefix}/manager/api/carousel.php`).done(function(data){
            const result = JSON.parse(data);
            console.log(result)
            homeSliders = [...result];
            let sliderTable = '';
            if(result && Array.isArray(result) && result.length > 0){
                result.forEach(function(item, index){
                    sliderTable += `<tr>
                        <td><a href="#/" class="previewImgBtn" id="check_${item.image ? item.image : ''}">查看图片</a></td>
                        <td>${item.title}</td>
                        <td>${item.content}</td>
                        <td>${item.link}</td>
                        <td>${item.item_index}</td>
                        <td>${item.type}</td>
                        <td>${item.button}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td><a href="#/" class="slider-edit-btn" id="slider${item.id}">编辑</a></td>
                    </tr>`;
                });
            }
            $('#sliderTableBody').html(sliderTable);
            $('#sliderTable').dataTable();
        });
    }
});