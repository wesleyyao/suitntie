import { prefix, showFloatMessage, useMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global.js';

$(document).ready(function () {
    $('#message').hide();
    $('#message').css('z-index', 1060);
    renderNav();

    $('#recommendContentDiv').hide();

    $('#programDetailsFormsDiv').load(`${prefix}/manager/modules/program/components/programDetails.html`);
    let program = window.location.href.split('?program=')[1];
    if (!program) {
        showFloatMessage('#message', useMessage('warning', `请求失败，请回到上一页重试。`), 3000);
        return;
    }
    program = decodeURIComponent(program);

    let programId = 0;
    let childPrograms = [];
    let courses = [];
    let info = [];
    let testimonials = [];
    let recommendations = [];
    let recommendContentId = 0;
    let recommendContentName = "";
    let type = '';
    let currentForm = '';
    let currentId = 0;
    fetchProgramData();

    $('.new-program-details').click(function () {
        const button = $(this).attr('id');
        $('.program-details-input').each(function () {
            $(this).val('');
        });
        $('.program-details-forms').each(function () {
            const form = $(this).attr('id');
            if (form.indexOf(button.replace('new', '')) > -1) {
                currentForm = form;
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
        currentId = 0;
        type = 'new';
        $('#programDetailsModal').modal('show');
    });

    $(document).on('submit', '.program-details-forms', function (e) {
        e.preventDefault();
        const operate = $(this).attr('id').replace('Form', '');
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        $.ajax({
            url: `${prefix}/manager/api/process-program.php?operate=${operate}&type=${type}&id=${currentId}&${operate === 'programRecommendContent' ? 'bid' : 'pid'}=${operate === 'programRecommendContent' ? recommendContentId : programId}`,
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data) {
                    const result = JSON.parse(data);
                    $('#programDetailsModal').modal('hide');
                    showFloatMessage('#message', useMessage(result.status, result.content), 3000);
                    $('#' + operate + 'Table').DataTable().destroy();
                    fetchProgramData();
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

    $(document).on('click', '.previewImgBtn', function () {
        const imgUrl = $(this).attr('id').replace('check_', '');
        const path = '' + imgUrl;
        if (imgUrl) {
            $('#imagePreviewMessage').html('');
            $('#previewImg').prop('src', path);
            $('#previewImg').show();
        }
        else {
            $('#imagePreviewMessage').html(generateMessage('warning', '没有上传图片'));
            $('#previewImg').hide();
        }
        $('#imageModal').modal('show');
    });

    $(document).on('click', '.rec-content', function () {
        const recId = $(this).attr('id').replace('recContent', '');
        const recName = $(this).attr('name');
        recommendContentId = recId;
        recommendContentName = recName;
        fetchRecContent(recId, recName);
    });

    function fetchRecContent(id, name) {
        if (recommendations && Array.isArray(recommendations) && recommendations.length > 0) {
            const foundData = recommendations.find(item => item.id == id);
            let recContentTableBody = '';
            if (foundData && foundData.content && Array.isArray(foundData.content) && foundData.content.length > 0) {
                foundData.content.forEach(function (item) {
                    recContentTableBody += `
                        <tr>
                            <td>${item.title ? item.title : ''}</td>
                            <td>${item.is_link ? item.is_link : ''}</td>
                            <td>${item.image ? `<img src="${prefix}${item.image}" style="width: 35px; height: auto;" />` : ''}</a></td>
                            <td>${item.url ? item.url : ''}</td>
                            <td>${item.author ? item.author : ''}</td>
                            <td>${item.douban ? item.douban : ''}</td>
                            <td>${item.status}</td>
                            <td><a href="#/" class="program-details-edit" id="recommendContent${item.id}">编辑</a></td>
                        </tr>`;
                });
            }
            else {
                recContentTableBody = `<tr>
                <td>${name}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td></tr>`;
            }
            $('#recommendationContentTableBody').html(recContentTableBody);
            $('#programRecommendContentTable').DataTable();
        }
        $('#recommendContentDiv').show();
    }

    $(document).on('click', '.program-details-edit', function () {
        const button = $(this).attr('id');
        let id = 0;
        let foundData = null;
        let formFor = '';
        if (button.indexOf('course') > -1) {
            id = button.replace('course', '');
            formFor = button.replace(id, '');
            foundData = courses.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#courseName').val(foundData.name);
            $('#courseContext').val(foundData.content);
            $('#courseIndex').val(foundData.item_index);
            $('#courseStatus').val(foundData.status);
        }
        else if (button.indexOf('info') > -1) {
            id = button.replace('info', '');
            formFor = button.replace(id, '');
            foundData = info.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#infoContent').val(foundData.content);
            $('#infoType').val(foundData.type);
            $('#infoIndex').val(foundData.p_index);
            $('#infoStatus').val(foundData.status);
        }
        else if (button.indexOf('testimonial') > -1) {
            id = button.replace('testimonial', '');
            formFor = button.replace(id, '');
            foundData = testimonials.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#testimonialName').val(foundData.name);
            $('#testimonialContent').val(foundData.feedback);
            $('#testimonialSchool').val(foundData.school);
            $('#testimonialGrade').val(foundData.grade);
            $('#testimonialStatus').val(foundData.status);
            $('#testimonialProgram').val(foundData.program);
        }
        else if (button.indexOf('recommendContent') > -1) {
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
        else if (button.indexOf('recommend') > -1) {
            id = button.replace('recommend', '');
            formFor = button.replace(id, '');
            foundData = recommendations.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#recTitle').val(foundData.title);
            $('#recIndex').val(foundData.item_index);
            $('#recStatus').val(foundData.status);
        }
        else {
            id = button.replace('child', '');
            formFor = button.replace(id, '');
            foundData = childPrograms.find(item => item.id == id);
            if (!foundData) {
                showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
                return;
            }
            $('#childTitle').val(foundData.name);
            $('#childContent').val(foundData.content);
            $('#childIndex').val(foundData.item_index);
            $('#childStatus').val(foundData.status);
        }
        $('.program-details-forms').each(function () {
            const form = $(this).attr('id').toLowerCase();
            if (form.indexOf(formFor.toLowerCase()) > -1) {
                currentForm = form;
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
        currentId = id;
        type = "update";
        $('#programDetailsModal').modal('show');
    });

    function fetchProgramData() {
        auth().then(
            (result) => {
                if (result.status === 'success') {
                    $.get(`${prefix}/manager/api/program-details.php?title=${decodeURIComponent(program)}`).done(function (data) {
                        const result = JSON.parse(data);
                        if (!result) {
                            $('#message').html(generateMessage('warning', `无法找到该专业的数据`));
                            return;
                        }
                        if (result.status === "NO_LOGIN") {
                            return;
                        }
                        let recommendTable = '';
                        programId = result.id;
                        recommendations = result.books;
                        if (recommendations && Array.isArray(recommendations) && recommendations.length > 0) {
                            recommendations.forEach(function (item) {
                                recommendTable += `<tr>
                        <td>${item.title}</td>
                        <td><img src="${prefix}${item.image}" style="width: 35px; height: auto" id="previewImg" alt="pic" /></td>
                        <td><a href="#/" id="recContent${item.id}" name="${item.title}" class="rec-content">查看</a></td>
                        <td>${item.item_index}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td><a href="#/" class="program-details-edit" id="recommend${item.id}">编辑</a></td></tr>`;
                            });
                        }
                        $('#recommendTableBody').html(recommendTable);
                        $('#programRecommendTable').DataTable();
                        if (recommendContentId !== 0) {
                            fetchRecContent(recommendContentId);
                        }

                        let childProgramTable = '';
                        childPrograms = result.child_programs;
                        if (childPrograms && Array.isArray(childPrograms) && childPrograms.length > 0) {
                            childPrograms.forEach(function (item) {
                                childProgramTable += `<tr>
                        <td width="140">${item.name}</td>
                        <td>${item.content}</td>
                        <td>${item.item_index}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td width="50"><a href="#/" class="program-details-edit" id="child${item.id}">编辑</a></td></tr>`;
                            });
                        }
                        $('#childProgramTableBody').html(childProgramTable);
                        $('#programChildTable').DataTable();

                        let courseTable = '';
                        courses = result.courses;
                        if (courses && Array.isArray(courses) && courses.length > 0) {
                            courses.forEach(function (item) {
                                courseTable += `<tr>
                        <td>${item.name}</td>
                        <td>${item.content}</td>
                        <td>${item.item_index}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td width="50"><a href="#/" class="program-details-edit" id="course${item.id}">编辑</a></td></tr>`;
                            });
                        }
                        $('#courseTableBody').html(courseTable);
                        $('#programCourseTable').DataTable();

                        let infoTable = '';
                        info = result.info;
                        if (info && Array.isArray(info) && info.length > 0) {
                            info.forEach(function (item) {
                                infoTable += `<tr>
                        <td>${item.content}</td>
                        <td width="100">${item.type == 'suitable' ? '你适合学' : item.type == 'brief' ? '专业简介' : '你准备好了吗'}</td>
                        <td>${item.p_index}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td width="50"><a href="#/" class="program-details-edit" id="info${item.id}">编辑</a></td></tr>`;
                            });
                        }
                        $('#programInfoTableBody').html(infoTable);
                        $('#programInfoTable').DataTable();

                        let testimonialTable = '';
                        testimonials = result.testimonials;
                        if (testimonials && Array.isArray(testimonials) && testimonials.length > 0) {
                            testimonials.forEach(function (item) {
                                testimonialTable += `<tr>
                        <td>${item.name}</td>
                        <td>${item.feedback}</td>
                        <td width="130">${item.school}</td>
                        <td width="100">${item.program}</td>
                        <td>${item.grade}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td width="50"><a href="#/" class="program-details-edit" id="testimonial${item.id}">编辑</a></td></tr>`;
                            });
                        }
                        $('#testimonialTableBody').html(testimonialTable);
                        $('#programTestimonialTable').DataTable();
                    });
                }
            }
        );
    }
});