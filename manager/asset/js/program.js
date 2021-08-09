import { prefix, showFloatMessage, useMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global.js';

$(document).ready(function () {
    $('#message').hide();
    $('#message').css('z-index', 1060);
    renderNav();
    $('#categoryFormDiv').load(`${prefix}/manager/modules/program/components/category.html`);
    $('#programFormDiv').load(`${prefix}/manager/modules/program/components/program.html`);

    fetchPrograms();
    let type = '';
    let currentId = 0;
    let programCategories = [];
    let programs = [];
    $(document).on('click', '.categoryView', function () {
        const button = $(this).attr('id');
        let id = 0;
        let foundCategory = undefined;
        if (button.indexOf('checkCategory') > -1) {
            id = button.replace('checkCategory', '');
            foundCategory = programCategories.find(item => item.id == parseInt(id));
            let programTable = '';
            if (!foundCategory) {
                showFloatMessage('#message', useMessage('warning', '该专业类别未找到，请刷新重试。'), 3000);
                return;
            }
            if (foundCategory.details && Array.isArray(foundCategory.details) && foundCategory.details.length > 0) {
                foundCategory.details.forEach(function (item) {
                    programTable += `
                        <tr>
                            <td>${item.title}</td>
                            <td>${item.description ? item.description : ''}</td>
                            <td><a href="./details.html?program=${item.title}">详情</a></td>
                        </tr>`;
                });
            }
            $('#programTableBody').html(programTable);
            $('#categoryDetailsModal').modal('show');
        }
        else {
            id = button.replace('editCategory', '');
            foundCategory = programCategories.find(item => item.id == parseInt(id));
            if (!foundCategory) {
                showFloatMessage('#message', useMessage('warning', '该专业类别未找到，请刷新重试。'), 3000);
                return;
            }
            $('#categoryId').val(foundCategory.id);
            $('#categoryName').val(foundCategory.name ? foundCategory.name : '');
            $('#categoryStatus').val(foundCategory.status ? foundCategory.status : 'close');
            $('#categoryIndex').val(foundCategory.item_index ? foundCategory.item_index : '0');
            $('#categoryImg').val('');
            currentId = foundCategory.id ? foundCategory.id : 0;
            type = 'update';
            $('#categoryModal').modal('show');
        }
    });

    $('#newCategoryBtn').click(function () {
        $(".category-field").each(function () {
            $(this).val('');
        });
        currentId = 0;
        type = 'new';
        $('#categoryModal').modal('show');
    });

    $(document).on('submit', '#categoryForm', function (e) {
        e.preventDefault();
        let form = $('#categoryForm')[0];
        let formData = new FormData(form);
        console.log(formData)
        $.ajax({
            url: `${prefix}/manager/api/program.php?to=${type}Category`,
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            success: function (data) {
                if (data) {
                    const result = JSON.parse(data);
                    if (result.file && result.file.status == 'failed') {
                        showFloatMessage('#message', useMessage('warning', result.file.message), 3000);
                    }
                    else {
                        if (result.is_saved) {
                            $('#categoryModal').modal('hide');
                            showFloatMessage('#message', useMessage('success', "保存成功！"), 3000);
                            $('#categoryTable').DataTable().destory();
                            fetchPrograms();
                        }
                        else {
                            showFloatMessage('#message', useMessage('warning', '未保存成功，请重试'), 3000);
                        }
                    }
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

    $(document).on('submit', '#programForm', function (e) {
        e.preventDefault();
        let form = $('#programForm')[0];
        let formData = new FormData(form);
        $.ajax({
            url: `${prefix}/manager/api/program.php?to=${type}Program`,
            type: 'POST',
            data: formData,
            success: function (data) {
                console.log(data)
                if (data) {
                    $('#programModal').modal('hide');
                    showFloatMessage('#message', useMessage('success', "保存成功！"), 3000);
                    $('#programTable').DataTable().destroy();
                    fetchPrograms();
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

    $('#newProgramBtn').click(function () {
        $(".program-field").each(function () {
            $(this).val('');
        });
        currentId = 0;
        type = 'new';
        $('#programStatus').val("open");
        const options = fetchProgramCategoryOptions();
        $('#programCateogryId').append(options);
        $('#programCateogryId').val(0);
        $('#programModalTitle').html('新增专业');
        $('#programModal').modal('show');
    });

    $(document).on('click', '.programView', function () {
        const button = $(this).attr('id');
        let id = 0;
        let foundProgram = undefined;
        if (button.indexOf('checkProgram') > -1) {
            id = button.replace('checkProgram', '');
            foundProgram = programs.find(item => item.id === parseInt(id));
            if (foundProgram) {
                window.location.href = `./details.html?programId=${id}`;
                return;
            }
            else {
                showFloatMessage('#message', useMessage('warning', '该专业未找到，请刷新重试'), 3000);
                return;
            }
        }
        else {
            id = button.replace('editProgram', '');
            foundProgram = programs.find(item => item.id === parseInt(id));
            if (!foundProgram) {
                showFloatMessage('#message', useMessage('warning', '该专业未找到，请刷新重试'), 3000);
                return;
            }
            $('#programName').val(foundProgram.title ? foundProgram.title : '');
            $('#programDesc').val(foundProgram.description ? foundProgram.description : '');
            $('#programRelated').val(foundProgram.related ? foundProgram.related : '');
            $('#programStatus').val(foundProgram.status ? foundProgram.status : 'close');
            $('#programId').val(id);
            const options = fetchProgramCategoryOptions();
            $('#programCateogryId').html(options);
            $('#programCateogryId').val(foundProgram.pc_id ? foundProgram.pc_id : 0);
            $('#programModalTitle').html('编辑专业');
            type = 'update';
            $('#programModal').modal('show');
        }
    });

    function fetchProgramCategoryOptions() {
        let options = '<option value="0">不绑定</option>';
        if (programCategories.length > 0) {
            programCategories.forEach(function (item) {
                options += `<option value="${item.id}">${item.name}</option>`;
            });
        }
        return options;
    }

    function fetchPrograms() {
        auth().then(
            (result) => {
                if (result.status === 'success') {
                    $.get(`${prefix}/manager/api/program.php`).done(function (data) {
                        const result = JSON.parse(data);

                        let categoryTable = '';
                        let programTable = '';
                        const categoryData = result ? result.categories : [];
                        const programData = result ? result.programs : [];
                        if (result.status === 'NO_LOGIN') {
                            return;
                        }
                        if (categoryData.length > 0) {
                            programCategories = categoryData;
                            categoryData.forEach(function (item) {
                                categoryTable += `<tr>
                        <td>${item.name}</td>
                        <td>${item.item_index}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td class="text-right">
                            <a href="#/" class="categoryView" id="checkCategory${item.id}">查看</a> | 
                            <a href="#/" class="categoryView" id="editCategory${item.id}">编辑</a>
                        </td>
                    </tr>`;
                            });
                        }
                        if (programData.length > 0) {
                            programs = programData;
                            programData.forEach(function (item) {
                                programTable += `<tr>
                        <td>${item.title}</td>
                        <td>${item.description ? item.description : ''}</td>
                        <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                        <td class="text-right">
                            <a href="./details.html?program=${item.title}">查看</a> | 
                            <a href="#/" class="programView" id="editProgram${item.id}">编辑</a>
                        </td>
                    </tr>`;
                            });
                        }
                        $('#programCategoryTableBody').html(categoryTable);
                        $('#programsTableBody').html(programTable);
                        $('#programCategoryTable').DataTable();
                        $('#programTable').DataTable();
                    });
                }
            }
        );
    }
});