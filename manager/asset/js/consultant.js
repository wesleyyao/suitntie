import { prefix, showFloatMessage, useMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global';

$(document).ready(function () {
    $('#message').hide();
    $('#message').css('z-index', 1060);
    renderNav();
    auth();
    let tutors = [];
    let regionData = [];
    let shcoolData = [];
    let type = '';
    let currentId = 0;
    let tutorId = 0;
    $('#consultantFormsDiv').load(`${prefix}/manager/modules/consultant/components/forms.html`);
    fetchConsultantData();

    $('.new-consultant-btn').click(function(){
        const button = $(this).attr('id');
        $('.consultant-input').each(function () {
            $(this).val('');
        });
        $('.consultant-forms').each(function () {
            const form = $(this).attr('id');
            if (form.indexOf(button.replace('new', '')) > -1) {
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
        currentId = 0;
        type = 'new';
        $('#consultantModal').modal('show');
    });

    $(document).on('submit', '.consultant-forms', function (e) {
        e.preventDefault();
        const operate = $(this).attr('id').replace('Form', '');
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        $.ajax({
            url: `${prefix}/manager/api/process-consultant.php?operate=${operate}&type=${type}&id=${currentId}`,
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data) {
                    const result = JSON.parse(data);
                    $('#consultantModal').modal('hide');
                    showFloatMessage('#message', useMessage(result.status, result.content), 3000);
                    $('#' + operate + 'Table').DataTable().destroy();
                    fetchConsultantData();
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

    $(document).on('click', '.edit-consultant-btn', function () {
        const button = $(this).attr('id');
        let id = 0;
        let foundData = null;
        let formFor = '';
        if (button.indexOf('tutor') > -1) {
            id = button.replace('tutor', '');
            formFor = button.replace(id, '');
            foundData = tutors.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            tutorId = id;
            $('#tutorName').val(foundData.name);
            $('#tutorNickName').val(foundData.nick_name);
            $('#tutorEducation').val(foundData.education);
            $('#tutorStatus').val(foundData.status);
            $('#tutorIntro').val(foundData.introduction);
            $('#currentTutorImg').val(foundData.thumbnail);
        }
        else if (button.indexOf('school') > -1) {
            id = button.replace('school', '');
            formFor = button.replace(id, '');
            foundData = shcoolData.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#schoolName').val(foundData.name);
            $('#schoolRegion').val(foundData.region);
            $('#schoolStatus').val(foundData.status);
        }
        else if (button.indexOf('region') > -1) {
            id = button.replace('region', '');
            formFor = button.replace(id, '');
            foundData = regionData.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#regionName').val(foundData.name);
            $('#regionIndex').val(foundData.r_index);
            $('#regionStatus').val(foundData.status);
        }
        else if(button.indexOf('recommendContent') > -1){
            id = button.replace('recommendContent', '');
            formFor = button.replace(id, '');
            const foundRec = recommendations.find(item => item.id == recommendContentId);
            foundData = foundRec && foundRec.content ? foundRec.content.find(item => item.id == id) : null;
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#contentTitle').val(foundData.title);
            $('#currentContentImgUrl').val(foundData.image);
            $('#contentStatus').val(foundData.status);
            $('#contentHasLink').val(foundData.is_link);
            $('#contentUrl').val(foundData.url);
            $('#contentAuthor').val(foundData.author);
            $('#contentDouban').val(foundData.douban);
        }
        else{

        }
        $('.consultant-forms').each(function () {
            const form = $(this).attr('id').toLowerCase();
            if (form.indexOf(formFor.toLowerCase()) > -1) {
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
        currentId = id;
        type = "update";
        $('#consultantModal').modal('show');
    });

    function fetchConsultantData(){
        $.get(`${prefix}/manager/api/consultant.php?tutor=all`).done(function(data){
            const result = JSON.parse(data);
            console.log(result);
            if(!result){
                return;
            }
            const { consultants, schools, regions } = result;
            tutors = consultants ? [...consultants] : [];
            let consultantTableBody = '';
            [...consultants].map(function(item){
                consultantTableBody += `<tr><td>${item.name ? item.name : ''}</td>
                <td>${item.nick_name ? item.nick_name : ''}</td>
                <td>${item.education ? item.education : ''}</td>
                <td>${item.introduction ? item.introduction : ''}</td>
                <td>${item.thumbnail ? `<img src="${prefix + item.thumbnail}" alt="" style="width: 90px; height: auto;" />` : `<i class="fas fa-user-circle h2"></i>`}</td>
                <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                <td width="90"><a href="#/" class="edit-consultant-btn" id="tutor${item.id}">编辑</a> | <a href="./details.html?tutor=${item.id}">专业</a></td>`;
            });
            $('#consultantTableBody').html(consultantTableBody);
            $('#consultantTable').dataTable();

            regionData = regions ? [...regions] : [];
            let regionTableBody = '';
            let schoolRegionOptions = '';
            [...regions].map(function(item){
                regionTableBody += `<tr>
                <td>${item.name}</td>
                <td>${item.r_index}</td>
                <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                <td><a href="#/" class="edit-consultant-btn" id="region${item.id}">编辑</a></td>`;
                schoolRegionOptions += `<option value="${item.id}">${item.name}</option>`;
            });
            $('#regionTableBody').html(regionTableBody);
            $('#regionTable').dataTable();
            $('#schoolRegion').append(schoolRegionOptions);

            shcoolData = schools ? [...schools] : [];
            let schoolTableBody = '';
            [...schools].map(function(item){
                schoolTableBody += `<tr>
                <td>${item.name}</td>
                <td>${regions.find(r => r.id === item.region)?.name}</td>
                <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                <td><a href="#/" class="edit-consultant-btn" id="school${item.id}">编辑</a></td>`;
            });
            $('#schoolTableBody').html(schoolTableBody);
            $('#schoolTable').dataTable();
        });
    }
});