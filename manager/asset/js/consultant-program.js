import { prefix, showFloatMessage, useMessage } from '../../../asset/js/common.js';
import { auth, renderNav } from './global.js';

$(document).ready(function () {
    $('#message').hide();
    $('#message').css('z-index', 1060);
    renderNav();
    $('#consultantProgramFormDiv').load(`${prefix}/manager/modules/consultant/components/program.html`);
    if (!window.location.search) {
        window.location.href = `${prefix}/manager/modules/consultant/index.html`;
    }
    let tutor = null;
    let type = 'new';
    let currentId = 0;
    const tutorId = window.location.search.split('?tutor=')[1];
    fetchConsultantPrograms(tutorId);

    $('#newProgram').click(function () {
        $('.program-input').each(function () {
            $(this).val('');
        });
        currentId = 0;
        type = 'new';
        $('#consultantProgramModal').modal('show');
    });

    $(document).on('click', '.edit-program-btn', function () {
        const id = $(this).attr('id').replace('program', '');
        const foundData = tutor && tutor.programs ? [...tutor.programs].find(item => item.id == id) : null;
        if (!foundData) {
            showFloatMessage('#message', useMessage('warning', '无法找到该数据。'), 3000);
            return;
        }
        currentId = id;
        console.log(foundData.school_id)
        $('#programId').val(foundData.program_id).trigger("change");
        $('#schoolId').val(foundData.school_id).trigger("change");
        $('#scholarship').val(foundData.scholarship);
        $('#education').val(foundData.education);
        $('#status').val(foundData.status);
        type = 'edit';
        $('#consultantProgramModal').modal('show');
    });

    $(document).on('submit', '#consultantProgramForm', function (e) {
        e.preventDefault();
        if (!tutor || !tutor.id) {
            showFloatMessage('#message', useMessage('warning', '无法找到导师数据。'), 3000);
            return;
        }
        let formFields = $(this)[0];
        let formData = new FormData(formFields);
        $.ajax({
            url: `${prefix}/manager/api/process-consultant.php?operate=consultantProgram&type=${type}&id=${currentId}&tutorId=${tutor.id}`,
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data) {
                    const result = JSON.parse(data);
                    $('#consultantProgramModal').modal('hide');
                    showFloatMessage('#message', useMessage(result.status, result.content), 3000);
                    $('#consultantProgramTable').DataTable().destroy();
                    fetchConsultantPrograms(tutorId);
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

    function fetchConsultantPrograms(tutorId) {
        auth().then(
            (result) => {
                if (result.status === 'success') {
                    $.get(`${prefix}/manager/api/consultant.php?tutor=${tutorId}`).done(function (data) {
                        const result = JSON.parse(data);
                        if (!result) {
                            return;
                        }
                        const { consultant, schools, regions, programs, status } = result;
                        if (status === 'NO_LOGIN') {
                            return;
                        }
                        tutor = consultant ? consultant : null;
                        let consultantProgramTableBody = '';
                        consultant.programs.map(function (item) {
                            const foundProgram = programs ? [...programs].find(p => p.id == item.program_id) : null;
                            const foundSchool = schools ? [...schools].find(s => s.id == item.school_id) : null;
                            consultantProgramTableBody += `<tr><td>${foundProgram ? foundProgram.title : ''}</td>
                <td>${item.education ? item.education : ''}</td>
                <td>${foundSchool ? foundSchool.name : ''}</td>
                <td>${item.scholarship ? item.scholarship : ''}</td>
                <td><span style="" class="badge bg-${item.status === 'open' ? 'success' : 'danger'} text-light">${item.status === 'open' ? '可用' : '禁用'}</span></td>
                <td width="90"><a href="#/" class="edit-program-btn" id="program${item.id}">编辑</a></td>`;
                        });
                        $('#tutorProgramTitle').text('导师' + consultant.nick_name + '的专业列表');
                        $('#consultantProgramTableBody').html(consultantProgramTableBody);
                        $('#consultantProgramTable').dataTable();

                        if (schools) {
                            const convertedSchools = schools.map(s => { return { id: s.id, text: s.name } });
                            $('#schoolId').select2({ data: convertedSchools });
                        }

                        if (programs) {
                            const convertedPrograms = programs.map(p => { return { id: p.id, text: p.title } });
                            $('#programId').select2({ data: convertedPrograms });
                        }
                    });
                }
            }
        );
    }
});