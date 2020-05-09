$(document).ready(function(){
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    $('#programMainDiv').html(loading);
    let programCards = '';
    $.get('/suitntie/public/api/program.php?type=all').done(function(data){
        if(data){
            const result = JSON.parse(data);
            console.log(result);
            if(Array.isArray(result) && result.length > 0){
                result.forEach(function(item){
                    programCards += `<div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="/suitntie${item.image}" width="60" alt="program logo"/>
                            <h5 class="card-title">${item.name}</h5>
                            <p>查看专业列表</p>
                        </div>
                    </div>
                </div>`
                });
                $('#programMainDiv').html(programCards);
            }
        }
    });
});