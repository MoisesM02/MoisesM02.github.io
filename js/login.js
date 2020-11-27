$(document).ready(function(){

    $.get("Backend/getEnterpriseInfo.php", function(response){
        let info = JSON.parse(response);
        let image ="";
        if(info.logo != "Sin logo"){
             img = `<img  alt="Foto de empresa" src="data:image/jpeg;base64,${info.logo}"/>`
        }else{
             img = `<h4>${info.logo} </h4>`
        }
        $('#logoContainer').html(img)
    })

    let showPassword = $('#ShowPassword');
    let PasswordVisibility = $('#Password');
    showPassword.on('change', function(){
        if(showPassword.prop('checked')){
            PasswordVisibility.attr('type', 'Text');
        }
        else{
            PasswordVisibility.attr('type', 'Password');
        }
    })


    $('#LoginForm').submit(function(e){
        e.preventDefault()
        let username = $('#Username').val()
        let password = $('#Password').val();;
        if (username != "" || username != null){
            if(password != "" || password != null){
                const data = {
                    'usuario': username,
                    'contra' : password
                };
                $.post("Backend/login.php", data, function(response){
                    try{
                        let resp = JSON.parse(response);
                        window.location.replace(resp.url);
                    }catch(e){
                        Swal.fire(response,'', 'error')
                    }
                })
            }
        }
    })

});