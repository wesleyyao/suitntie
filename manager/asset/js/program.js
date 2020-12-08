$(document).ready(function(){
    fetchPrograms();
    let programCategories = [];
    let programs = [];
    let programDataTable = null;
    $(document).on('click', '.categoryView', function(){
        const button = $(this).attr('id');
        let id = 0;
        let foundCategory = undefined;
        if(button.indexOf('checkCategory') > -1){
            id = button.replace('checkCategory', '');
            foundCategory = programCategories.find(item => item.id == parseInt(id));
            let programTable = '';
            if(!foundCategory){
                $('#message').html(generateMessage('warning', '该专业类别未找到，请刷新重试。'));
                return;
            }
            if(foundCategory.details && Array.isArray(foundCategory.details) && foundCategory.details.length > 0){
                foundCategory.details.forEach(function(item){
                    programTable += `
                        <tr>
                            <td>${item.title}</td>
                            <td>${item.description ? item.description : ''}</td>
                            <td><a href="./program-details.php?program=${item.title}">详情</a></td>
                        </tr>`;
                });
            }
            $('#programTableBody').html(programTable);
            $('#categoryDetailsModal').modal('show');
        }
        else{
            id = button.replace('editCategory', '');
            foundCategory = programCategories.find(item => item.id == parseInt(id));
            if(!foundCategory){
                $('#message').html(generateMessage('warning', '该专业类别未找到，请刷新重试。'));
                return; 
            }
            $('#editCategoryName').val(foundCategory.name ? foundCategory.name : '');
            $('#editCategoryStatus').val(foundCategory.status ? foundCategory.status : 'close');
            $('#editCategoryIndex').val(foundCategory.item_index ? foundCategory.item_index : '0');
            $('#editCategoryId').val(foundCategory.id ? foundCategory.id : 0);
            $('#editCategoryImg').val('');
            $('#editCategoryModal').modal('show');
        }
    });

    $('#newCategoryForm').submit(function(e){
        e.preventDefault();
        let form = $('#newCategoryForm')[0];

        let formData = new FormData(form);

        $.ajax({
            url: './api/program.php?to=addNewCategory',  
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            success: function(data){
                if(data){
                    const result = JSON.parse(data);
                    if(result.file && result.file.status == 'failed'){
                        $('#newCategoryMessage').html(generateMessage('warning', result.file.message));
                    }
                    else{
                        if(result.is_saved){
                            window.location.href = "./programs.php";
                        }
                        else{
                            $('#newCategoryMessage').html(generateMessage('warning', '未保存成功，请重试。'));
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

    $('#editCategoryForm').submit(function(e){
        e.preventDefault();
        let form = $('#editCategoryForm')[0];
        let formData = new FormData(form);

        $.ajax({
            url: './api/program.php?to=updateCategory',  
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            success: function(data){
                if(data){
                    const result = JSON.parse(data);
                    if(result.file && result.file.status == 'failed'){
                        $('#editCategoryMessage').html(generateMessage('warning', result.file.message));
                    }
                    else{
                        if(result.is_saved){
                            window.location.href = "./programs.php";
                        }
                        else{
                            $('#editCategoryMessage').html(generateMessage('warning', '未更新成功，请重试。'));
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

    $('#programForm').submit(function(e){
        e.preventDefault();
        let form = $('#programForm')[0];
        let formData = new FormData(form);
        const isNew = parseInt($('#isNew').val());
        const url = isNew === 1 ? './api/program.php?to=addNewProgram' : './api/program.php?to=updateProgram';
        $.ajax({
            url: url,  
            type: 'POST',
            data: formData,
            success: function(data){ console.log(data)
                if(data){
                   //window.location.href = "./programs.php";
                   $('#programsTable').DataTable().destroy(); 
                   fetchPrograms();

                }
                else{
                    $('#programMessage').html(generateMessage('warning', '未更新成功，请重试。'));
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

    $('#newProgramBtn').click(function(){
        $('#programName').val("");
        $('#programDesc').val("");
        $('#programRelated').val("");
        $('#programStatus').val("open");
        const options = fetchProgramCategoryOptions();
        $('#programCateogryId').append(options);
        $('#programCateogryId').val(0);
        $('#programModalTitle').html('新增专业');
        $('#isNew').val(1);
        $('#programModal').modal('show');
    });

    $(document).on('click', '.programView', function(){
        const button = $(this).attr('id');
        let id = 0;
        let foundProgram = undefined;
        if(button.indexOf('checkProgram') > -1){
            id = button.replace('checkProgram', '');
            foundProgram = programs.find(item => item.id === parseInt(id));
            if(foundProgram){
                window.location.href= `./program-details.php?programId=${id}`;
                return;
            }
            else{
                $('#message').html(generateMessage('warning', '该专业未找到，请刷新重试。'));
                return;
            }
        }
        else{
            id = button.replace('editProgram', '');
            foundProgram = programs.find(item => item.id === parseInt(id));
            if(!foundProgram){
                $('#message').html(generateMessage('warning', '该专业未找到，请刷新重试。'));
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
            $('#isNew').val(0);
            $('#programModal').modal('show');
        }
    });

    function fetchProgramCategoryOptions(){
        let options = '<option value="0">不绑定</option>';
        if(programCategories.length > 0){
            programCategories.forEach(function(item){
                options += `<option value="${item.id}">${item.name}</option>`;
            });   
        }
        return options;
    }

    function fetchPrograms(){
        $.get('./api/program.php').done(function(data){
            const result = JSON.parse(data);
            console.log(result);
            let categoryTable = '';
            let programTable = '';
            const categoryData = result ? result.categories : [];
            const programData = result ? result.programs : [];
            if(categoryData.length > 0){
                programCategories = categoryData;
                categoryData.forEach(function(item){
                    categoryTable += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.item_index}</td>
                        <td>${item.status}</td>
                        <td class="text-right">
                            <a href="#/" class="categoryView" id="checkCategory${item.id}">查看</a> | 
                            <a href="#/" class="categoryView" id="editCategory${item.id}">编辑</a>
                        </td>
                    </tr>`;
                });
            }
            if(programData.length > 0){
                programs = programData;
                programData.forEach(function(item){
                    programTable += `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.title}</td>
                        <td>${item.description ? item.description : ''}</td>
                        <td>${item.status}</td>
                        <td class="text-right">
                            <a href="./program-details.php?program=${item.title}">查看</a> | 
                            <a href="#/" class="programView" id="editProgram${item.id}">编辑</a>
                        </td>
                    </tr>`;
                });
            }
            $('#programCategoryTableBody').html(categoryTable);
            $('#programsTableBody').html(programTable);
            programDataTable= $('#programsTable').DataTable();
        });
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
});