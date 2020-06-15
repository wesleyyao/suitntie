$(document).ready(function(){
    console.log(window.location.href);
    let program = window.location.href.split('?program=')[1];
    if(!program){
        $('#message').html(generateMessage('warning', `请求失败，请回到上一页重试。`));
        return;
    }
    program = decodeURIComponent(program);
    let programId = 0;
    let books = [];
    $.get(`./api/program-details.php?title=${decodeURIComponent(program)}`).done(function(data){
        const result = JSON.parse(data);
        console.log(result);
        if(!result){
            $('#message').html(generateMessage('warning', `无法找到该专业的数据`));
            return; 
        }
        let recommendTable = '';
        books = result.books;
        programId = result.id;
        const recommendations = result.books;
        if(recommendations && Array.isArray(recommendations) && recommendations.length > 0){
            recommendations.forEach(function(item){
                recommendTable += `<tr>
                    <td>${item.id}</td>
                    <td>${item.title}</td>
                    <td><a href="#/" class="previewImgBtn" id="check_${item.image ? item.image : ''}">查看图片</a></td>
                    <td>${item.content && Array.isArray(item.content) && item.content.length > 0 ? `<a href="#/" id="book${item.id}" class="book-content">查看</a>` : ''}</td>
                    <td>${item.item_index}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/program-recommendation.php?type=edit&id=${item.id}&pid=${programId}&title=${program}">编辑</a></td></tr>`;
            });
        }
        $('#recommendTableBody').html(recommendTable);
        $('#recommendTable').dataTable();
        $('#newRecommendBtn').prop('href', `./components/program-recommendation.php?type=new&id=0&pid=${programId}&title=${program}`);

        let childProgramTable = '';
        const childPrograms = result.child_programs;
        if(childPrograms && Array.isArray(childPrograms) && childPrograms.length > 0){
            childPrograms.forEach(function(item){
                childProgramTable += `<tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.content}</td>
                    <td>${item.item_index}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/program-children.php?type=edit&id=${item.id}&pid=${programId}&title=${program}">编辑</a></td></tr>`;
            });
        }
        $('#childProgramTableBody').html(childProgramTable);
        $('#childProgramTable').dataTable();
        $('#newChildProgramBtn').prop('href', `./components/program-children.php?type=new&id=0&pid=${programId}&title=${program}`);

        let courseTable = '';
        const courses = result.courses;
        if(courses && Array.isArray(courses) && courses.length > 0){
            courses.forEach(function(item){
                courseTable += `<tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.content}</td>
                    <td>${item.item_index}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/program-course.php?type=edit&id=${item.id}&pid=${programId}&title=${program}">编辑</a></td></tr>`;
            });
        }
        $('#courseTableBody').html(courseTable);
        $('#courseTable').dataTable();
        $('#newCourseBtn').prop('href', `./components/program-course.php?type=new&id=0&pid=${programId}&title=${program}`);

        let infoTable = '';
        const info = result.info;
        if(info && Array.isArray(info) && info.length > 0){
            info.forEach(function(item){
                infoTable += `<tr>
                    <td>${item.id}</td>
                    <td>${item.content}</td>
                    <td>${item.type}</td>
                    <td>${item.p_index}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/program-info.php?type=edit&id=${item.id}&pid=${programId}&title=${program}">编辑</a></td></tr>`;
            });
        }
        console.log(programId)
        $('#programInfoTableBody').html(infoTable);
        $('#programInfoTable').dataTable();
        $('#newInfoBtn').prop('href', `./components/program-info.php?type=new&id=0&pid=${programId}&title=${program}`);

        let testimonialTable = '';
        const testimonials = result.testimonials;
        if(testimonials && Array.isArray(testimonials) && testimonials.length > 0){
            testimonials.forEach(function(item){
                testimonialTable += `<tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.feedback}</td>
                    <td>${item.school}</td>
                    <td>${item.program}</td>
                    <td>${item.grade}</td>
                    <td>${item.status}</td>
                    <td><a href="./components/program-testimonial.php?type=edit&id=${item.id}&pid=${programId}&title=${program}">编辑</a></td></tr>`;
            });
        }
        $('#testimonialTableBody').html(testimonialTable);
        $('#testimonialTable').dataTable();
        $('#newTestimonialBtn').prop('href', `./components/program-testimonial.php?type=new&id=0&pid=${programId}&title=${program}`);
    });

    $(document).on('click', '.previewImgBtn', function(){
        const imgUrl = $(this).attr('id').replace('check_', '');
        const path = '/suitntie' + imgUrl;
        if(imgUrl){
            $('#imagePreviewMessage').html('');
            $('#previewImg').prop('src', path);
            $('#previewImg').show();
        }
        else{
            $('#imagePreviewMessage').html(generateMessage('warning', '没有上传图片'));
            $('#previewImg').hide();
        }
        $('#imageModal').modal('show');
    });

    $(document).on('click', '.book-content', function(){
        const bookId = $(this).attr('id').replace('book', '');
        if(books && Array.isArray(books) && books.length > 0){
            const foundBook = books.find(item => item.id === parseInt(bookId)); console.log(foundBook)
            if(foundBook && foundBook.content && Array.isArray(foundBook.content) && foundBook.content.length > 0){
                let contentTableBody = '';
                foundBook.content.forEach(function(item){
                    contentTableBody += `
                        <tr>
                            <td>${item.title ? item.title : ''}</td>
                            <td>${item.is_link ? item.is_link : ''}</td>
                            <td><a href="#/" class="previewImgBtn" id="check_${item.image ? item.image : ''}">查看图片</a></td>
                            <td>${item.url ? item.url : ''}</td>
                            <td>${item.author ? item.author : ''}</td>
                            <td>${item.douban ? item.douban : ''}</td>
                            <td>${item.status}</td>
                            <td><a href="./components/program-recommendation-content.php?type=edit&id=${item.id}&pid=${programId}&title=${program}&bId=${bookId}">编辑</a></td>
                        </tr>`;
                });
                $('#recommendationContentTableBody').html(contentTableBody);
            }
        }
        $('#newRecommendationContent').prop('href', `./components/program-recommendation-content.php?type=new&id=0&pid=${programId}&title=${program}&bId=${bookId}`);
        $('#recommendationContentModal').modal('show');
    });

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