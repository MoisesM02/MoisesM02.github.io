$(function(){
    $.get('Backend/getEnterpriseInfo.php', function(response){
        
        let res = JSON.parse(response);
        
        $('#enterpriseName').text(res.nombre);
    });
})