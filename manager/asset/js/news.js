$(document).ready(function(){
    let sliderTable = '';
    $.get(`/suitntie/public/api/home.php`).done(function(data){
        const result = JSON.parse(data);
        console.log(result)
        if(result && Array.isArray(result) && result.length > 0){
            result.forEach(function(item, index){
                sliderTable += `
                    <tr>
                    <td><a href="#/" class="previewImgBtn" id="check_${item.image ? item.image : ''}">查看图片</a></td>
                    <td>${item.title}</td>
                    <td>${item.content}</td>
                    <td>${item.link}</td>
                    <td>${item.item_index}</td>
                    <td>${item.type}</td>
                    <td>${item.button}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/slider.php?type=edit&id=${item.id}">编辑</a></td>
                </tr>`;
            });
        }
        $('#sliderTableBody').html(sliderTable);
        $('#sliderTable').dataTable();
    });

    $(document).on('click', '.previewImgBtn', function(){
        const path = '/suitntie' + $(this).attr('id').replace('check_', '');
        $('#previewImg').prop('src', path);
        $('#imageModal').modal('show');
    });
});