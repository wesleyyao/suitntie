$(document).ready(function(){
    const loading = `
    <div class="d-flex justify-content-center m-2">
        <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>`;
    $.get('/suitntie/public/api/program.php').done(function(data){
        console.log(data)
        if(data){
            const result = JSON.parse(data);
            console.log(result);
        }
    });
});