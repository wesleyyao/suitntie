$(document).ready(function(){
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    $('#programMainDiv').html(loading);
    let programCards = '';
    let programData = [];
    const currentPage = window.location.href;
    $.get('/suitntie/public/api/program.php?type=all').done(function(data){
        if(data){
            const result = JSON.parse(data);
            programData = result;
            if(Array.isArray(result) && result.length > 0){
                result.forEach(function(item){
                    programCards += `<div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="/suitntie${item.image}" width="60" alt="program logo"/>
                            <h5 class="card-title">${item.name}</h5>
                            <a href="#/" class="programs-view-btn" id="programsView${item.id}">查看专业列表</a>
                        </div>
                    </div>
                </div>`
                });
                $('#programMainDiv').html(programCards);
            }
        }
    });


    $(document).on('click', '.programs-view-btn', function(){
        const pcId = $(this).attr('id').replace('programsView', '');
        if(programData.length == 0){
            return;
        }
        let programDetails = '<ul>';
        const foundCategory = programData.find(item => item.id == pcId);
        console.log(pcId)
        if(foundCategory && foundCategory.details && Array.isArray(foundCategory.details)){
            foundCategory.details.forEach(function(item){
                programDetails += `<li><a href="/suitntie/programs/explore.php?title=${item.title}">${item.title}</a></li>`;
            });
        }
        programDetails += '</ul>';
        $('#programDetailsDiv').html(programDetails);
        $('#programDetailsModal').modal('show');
    });

    if(currentPage.includes('title=')){
        const title = decodeURIComponent(currentPage.slice(currentPage.indexOf('title=') + 6));
        $.get('/suitntie/public/api/program.php?view=single&title=' + title).done(function(data){
            const result = JSON.parse(data);
            console.log(result)
            if(!result){
                return;
            }
            const info = result.info;
            let briefData = info && Array.isArray(info) && info.length > 0 ?
                info.filter(item => item.type === 'brief') : [];
            let brief = '';
            if(briefData.length > 0){
                briefData = briefData.sort((a, b) => a.p_index - b.p_index);
                briefData.forEach(function(item){
                    brief += `<p>${item.content}</p>`;
                });
            }
            $('#brief').html(brief);

            const relatedPrograms = result.related_programs && Array.isArray(result.related_programs) && result.related_programs.length > 0 ?
                result.related_programs : [];
            let related = '<ul>';
            if(relatedPrograms.length > 0){
                relatedPrograms.forEach(function(item){
                    if(item.title !== title){
                        related += `<li><a href="/suitntie/programs/explore.php?title=${item.title}">${item.title}</a></li>`;
                    }
                });
            }
            related += '</ul>';
            $('#relatedPrograms').html(related);

            let suitableData = info && Array.isArray(info) && info.length > 0 ? info.filter(item => item.type === 'suitable') : [];
            let suitable = '';
            if(suitableData.length > 0){
                suitableData = suitableData.sort((a, b) => a.p_index - b.p_index);
                suitableData.forEach(function(item){
                    suitable += `<p>${item.content}</p>`;
                });
            }
            $('#ifSuitable').html(suitable);

            let readyData = info && Array.isArray(info) && info.length > 0 ? info.filter(item => item.type === 'ready') : [];
            let ready = '<ul>';
            if(readyData.length > 0){
                readyData = readyData.sort((a, b) => a.p_index - b.p_index);
                readyData.forEach(function(item){
                    ready += `<li>${item.content}</li>`;
                });
            }
            ready += '</ul>';
            $('#ready').html(ready);

            const bookData = result.books;
            let books = `<div class="row">`;
            if(bookData && Array.isArray(bookData) && bookData.length > 0){
                bookData.forEach(function(item){
                    books += `<div class="col-lg-4 col-md-12 col-sm-12" style="display: flex">
                        <img src="/suitntie/${item.image}" alt="${item.title}" width="120" height="90" />
                        <div>
                            <h6>${item.title}</h6>
                            <p>作者： ${item.author}</p>
                            <p>豆瓣评分： ${item.douban}</p>
                        </div>
                        </div>
                    `;
                });
            }
            books += `</div>`;
            $('#recommend').html(books);

            const courseData = result.courses;
            let courses = `<div class="accordion" id="courseAccordion">`;
            if(courseData && Array.isArray(courseData) && courseData.length > 0){
                courseData.forEach(function(item, index){
                    courses += `
                        <div class="card">
                            <div class="card-header" id="courseHeader${index}">
                                <h2 class="mb-0">
                                    <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#courseCollapse${index}" aria-expanded="true" aria-controls="courseCollapse${index}">
                                        ${item.name}
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="courseCollapse${index}" class="collapse ${index == 0 ? 'show' : ''}" aria-labelledby="courseHeader${index}" data-parent="#courseAccordion">
                                <div class="card-body">
                                    <p>${item.content}</p>
                                </div>
                            </div>
                        </div>`;
                });
            }
            courses += `</div>`;
            $('#courses').html(courses);

            const childProgramData =result.child_programs;
            let childPrograms = `<div class="accordion" id="childAccordion">`;
            if(childProgramData && Array.isArray(childProgramData) && childProgramData.length > 0){
                childProgramData.forEach(function(item, index){
                    childPrograms += `
                        <div class="card">
                            <div class="card-header" id="childHeader${index}">
                                <h2 class="mb-0">
                                    <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#childCollapse${index}" aria-expanded="true" aria-controls="childCollapse${index}">
                                        ${item.name}
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="childCollapse${index}" class="collapse ${index == 0 ? 'show' : ''}" aria-labelledby="childHeader${index}" data-parent="#childAccordion">
                                <div class="card-body">
                                    <p>${item.content}</p>
                                </div>
                            </div>
                        </div>`;
                });
            }
            childPrograms += `</div>`;
            $('#childPrograms').html(childPrograms);
        });
    }
});