$(function(){
    let url;
    let tipo;
    function cargarSelects(tipos){       
    $.post('Backend/crear-editar-selects.php', {tipos}, function(response){
        console.log(tipos);
        try{ 
            res = JSON.parse(response);
            let template = "";
            res.forEach(registro => {
                template += `
                    <option value="${registro.id}">${registro.nombre} </option>
                `;
            });
            $('#selectNombre').html(template);
            cargarEstado();
       }catch(error){
           Swal.fire(response);
       }
    })
    }



    $(document).on('click', '.crear', function(){
        tipo = $(this).attr('data-type');
        url = "Backend/crear.php";
        $('#nombre1').text(`Nombre de ${tipo}`)
        $('#Nombre').attr('placeholder', `Nombre de ${tipo}`)
        $('#formularioCrear').modal('show');
    })

    $('#crear').on('click', function(){
        let nombre = $('#Nombre').val();
        const data = {
            nombre,
            tipo
        }
        $('#Nombre').val("");
        $.post(url, data, function(res){
            Swal.fire(res)
        })
    })

    //editar
    $(document).on('click', '.editar', function(){
        tipo = $(this).attr('data-type');
        let template="";
        if(tipo == "Empleada"){
            template = `
                <option value="Disponible">Disponible</option>
                <option value="No disponible">No disponible</option>
            `;
        }else{
            template = `
                <option value ="Disponible">Disponible</option>
                <option value ="Ocupada">Ocupada</option>
                <option value ="Mantenimiento">Mantenimiento</option>
                <option value ="Limpiando">Limpiando</option>
            `;;
        }
        $('#selectEstado').html(template);
        url = "Backend/editar.php";
        $('#Nombre2').text(`Nombre de ${tipo}`)
        cargarSelects(tipo);
        
        $('#formularioEditar').modal('show');
    });

    $('#editarEmpresa').on('click', function(){
        $('#empresaEditar').modal('show');
    })

    $('#btnActualizar').on('click', function(){
        let imageData = $('#logo').prop('files')[0];
        let nombre = $('#nombreEmpresa').val();
        console.log(nombre)
        var formData = new FormData();
        formData.append('image',imageData);
        formData.append('nombre', nombre);
        Swal.fire({
            title:"Actualización de datos",
            text: '¿Está seguro de querer actualizar esta información?',
            icon: 'warning',
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false
          }).then((result) =>{
            Swal.fire({
              title: 'Por favor espere',
              html: `<div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>`
            })
            if(result.isConfirmed){
        $.ajax({
            url         :   'Backend/editarEmpresa.php',
            dataType    :   'text',
            cache       :   false,
            contentType :   false,
            processData :   false,
            data        :   formData,
            type        :   'post',
            success     :   function(response){
            try{
              Swal.close();
              let res = JSON.parse(response);
              Swal.fire(res.msg,'', 'success');
              $('#actualizarEmpresa').trigger('reset');
            }catch(error){
              Swal.close();
              Swal.fire(response, '', 'error');
            }
          
           
            }
        })//End ajax petition
          }else{
              Swal.fire("Cancelado", 'Se ha cancelado la actualización de datos', 'error');
          }
        })
    });

    function cargarEstado(){
        let id = $('#selectNombre').val();
            const data = {
                id,
                tipo
            }
            $.post("Backend/obtener-estado.php",data , function(response){
                try{
                    let res = JSON.parse(response);
                    let template = `<strong>${res.estado}</strong>`;
                    $('#estadoActual').html(template);
                }catch(error){
                    Swal.fire(response);
                }
            })
    }
    $('#selectNombre').on('change', function(){
        cargarEstado();
    });
    $('#editar').on('click', function(){
        let id = $('#selectNombre').val();
        let estado = $('#selectEstado').val();
        const data = {
            id,
            estado,
            tipo
        };
        $('#Nombre').val("");
        Swal.fire({
            title: "Confirmar",
            text: "¿Está seguro de querer editar este elemento?",
            icon: "question",
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
            $.post("Backend/actualizar-estado.php", data, function(response){
                Swal.fire(response);
            })

        }else{
            Swal.fire("Cancelado", '', 'error')
        }
    })
    });

    $('#eliminar').on('click', function(){
        let id = $('#selectNombre').val();
        let estado = $('#selectEstado').val();
        const data = {
            id,
            estado,
            tipo
        };
        Swal.fire({
            title: "Eliminar",
            text: "¿Desea eliminar este elemento?",
            icon: "question",
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("Backend/eliminar-estado.php", data, function(response){
                    Swal.fire(response);
                })
        }else{
            Swal.fire("Cancelado", '', 'error')
        }
        })

    })
})