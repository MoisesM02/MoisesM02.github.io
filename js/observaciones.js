$(function(){
    moment.locale('es')
    let initialDate;
    $('#form').hide();
    // Dar formato de DUI al textbox
    var cleave = new Cleave('#buscador',{
        numericOnly:true,
        blocks:[8,1],
        delimiter: '-'
      })
    var cleave2 = new Cleave('#DUI',{
        numericOnly:true,
        blocks:[8,1],
        delimiter: '-'
      })

    $('#buscar').on('click', function(){
        Swal.fire({
            title: "Por favor espere",
            html: `
            Cargando... <br> <br>
            <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
            </div>
            `
        });
        let dui = $('#buscador').val();
        $.post('Backend/obtenerInformacionPersonal.php', {dui}, function(response){
            
            try{
                let foto = ""
                let estado;
                resp = JSON.parse(response);
                
                resp[0].forEach(empleado => {
                    initialDate = moment(empleado.fechaNacimiento, 'MM-DD-YYYY').format('YYYY-MM-DD')
                    $('#nombres').val(empleado.nombres)
                    $('#apellidos').val(empleado.apellidos)
                    $('#residencia').val(empleado.residencia)
                    $('#edad').val(empleado.edad)
                    $('#DUI').val(empleado.dui)
                    $('#idEmpleado').val(empleado.id)
                    $('#fecha').val(moment(empleado.fechaNacimiento, 'MM-DD-YYYY').format('LL'))
                    foto = `<img class="img" alt="Foto de empleado" src="data:image/jpeg;base64,${empleado.imagen}" />`
                    estado = empleado.estado;
                    
                   


                });
                let template = `<option value="${estado}">${estado}</option>
                                <option value="Contratado">Contratado</option>
                                <option value="Despedido">Despedido</option>          
                `
                if(Array.isArray(resp[1])){
                    
                    let observacionesTemplate = "";
                    let observaciones = resp[1];
                    for(let i = 0; i< observaciones.length; i++){
                        
                        observacionesTemplate += `
                        <div class="observacion card my-1" data-id="${observaciones[i].idObservacion}" id="observacion${observaciones[i].idObservacion}"> 
                            <div class="card-body"> 
                                <span><strong>${observaciones[i].username}</strong> ${moment(observaciones[i].fecha, 'YYYY-MM-DD').format('LL')}</span>
                                <p>${observaciones[i].observacion} </p>
                                <a href="#" class="eliminar" style="margin-left:3%; margin-bottom:1%;" data-id="${observaciones[i].idObservacion}">Eliminar </a>
                            </div>
                        </div>`
                    }
                    $('.observaciones').html(observacionesTemplate);
                }else{
                    let observacionesTemplate = "No hay observaciones";
                    $('.observaciones').html(observacionesTemplate);
                }

                $('#estado').html(template)
                $('.foto').html(foto)
                $('#form').show(500);
                
                Swal.close();
            }catch(error){
                Swal.close();
                Swal.fire(response, '', 'error');
            }
        })
    });

    //Eliminar información del empleado
    $('#eliminarInfo').on('click', function(){
        let id = $('#idEmpleado').val();
        Swal.fire({
            title:"Eliminar información",
            text: '¿Está seguro de querer eliminar esta información?',
            icon: 'warning',
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false
          }).then((result) =>{
            if(result.isConfirmed){
                Swal.fire({
                    title: "Por favor espere",
                    html: `
                    Cargando... <br> <br>
                    <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    `  
                 })
                $.post('Backend/eliminarInformacionPersonal.php', {id}, function(response){
                    try{
                        let res = JSON.parse(response);
                        Swal.close();
                        Swal.fire(res.msg, '', 'success').then(function(){location.reload()})
                    }catch(error){
                        Swal.close();
                        Swal.fire(response, '', 'error')
                    }
                });
            }else{

            }
          })

    })


    //Actualizar observación
    $('#editarInfo').on('click', function(e){
        e.preventDefault();

                let id = $('#idEmpleado').val()
                let apellidos = $('#apellidos').val();
                let residencia =    $('#residencia').val();
                let edad = $('#edad').val();
                let dui =  $('#DUI').val();
                let nombres = $('#nombres').val();
                let estado = $('#estado').val();
                let fecha = initialDate;

                const data = {
                    id,
                    apellidos,
                    residencia,
                    edad,
                    dui,
                    nombres,
                    estado,
                    fecha
                }
                Swal.fire({
                    title:"Editar información",
                    text: '¿Está seguro de querer editar esta información?',
                    icon: 'warning',
                    showConfirmButton: true,
                    showDenyButton: true,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    allowOutsideClick: false
                  }).then((result) =>{
                    if(result.isConfirmed){
                        Swal.fire({
                            title: "Por favor espere",
                            html: `
                            Cargando... <br> <br>
                            <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                            `  
                         });
                $.post('Backend/actualizarInformacionPersonal.php', data, function(response){
                    try{
                        let res = JSON.parse(response);
                        Swal.fire(res.msg, '', 'success');
                    }catch(error){
                        Swal.fire(response, '', 'error')
                    }
                })
            }else{
                Swal.fire("Cancelado", "", "error")
            }
        })
    })

    //Eliminar observación
    $(document).on('click', '.eliminar', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        Swal.fire({
            title:"Añadir observación",
            text: '¿Está seguro de crear esta observación?',
            icon: 'warning',
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false
          }).then((result) =>{
            if(result.isConfirmed){
                Swal.fire({
                    title: "Por favor espere",
                    html: `
                    Cargando... <br> <br>
                    <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    `  
                 });
                $.post('Backend/eliminarObservacion.php', {id}, function(response){
                try{
                    let resp = JSON.parse(response);
                    Swal.close();
                    Swal.fire(resp.msg, '', 'success');
                    $(`#observacion${id}`).remove();
                }catch(error){
                    Swal.close();
                    Swal.fire(response, '', 'error')
                }
                });
            }else{
                Swal.fire("Cancelado", "", "error")
            }
        })
    })

    
    // daterangepicker
    $('#dateRange span').daterangepicker(
        {
          
           singleDatePicker: true,
           showDropdowns: true,
           showWeekNumbers: true,
           minYear: 1970,
           maxYear: parseInt(moment().subtract(18, 'years').format('YYYY')),
           opens: 'center',
           buttonClasses: ['btn btn-default'],
           applyClass: 'btn-small btn-primary',
           cancelClass: 'btn-small',
           dateFormat: 'DD-MMMM-YYYY:',
           timeFormat:  "hh:mm:ss",
           separator: ' Hasta ',
           maxDate: moment().format('YYYY-MM-D HH:mm:ss'),
           locale: {
               applyLabel: 'Confirmar',
               cancelLabel: 'Cancelar',
               fromLabel: 'Desde',
               toLabel: 'Hasta',
               customRangeLabel: 'Rango específico',
               daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
               monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
               firstDay: 1
           }
        },
        function(start, end) {
         $('#dateRange span').html(start.format('LL'));
        initialDate = start.format('YYYY-MM-DD');
        let now = moment()
        let edad = now.diff(start, 'years')
        $('#edad').val(edad);
        $('#fecha').val(start.format('LL'))
        }
     );
     //Set the initial state of the picker label
     $('#dateRange span').html(moment().subtract(18,'years').format('LL'));
 
    
     //Mostrar modal
     $('#agregarObservacion').on('click', function(){
        $('#formularioObservaciones').modal('show')
     })

     //Crear Observación
     $('#crearObservacion').on('click', function(){
         let dui = $('#DUI').val();
         let idEmpleado = $('#idEmpleado').val();
         let observacion = $('#observacion').val();
         let username = $('#username').val();
         const data = {
             dui,
             idEmpleado,
             observacion,
             username
         }

         Swal.fire({
            title:"Añadir observación",
            text: '¿Está seguro de crear esta observación?',
            icon: 'warning',
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false
          }).then((result) =>{
            if(result.isConfirmed){
                
         Swal.fire({
            title: "Por favor espere",
            html: `
            Cargando... <br> <br>
            <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
            </div>
            `  
         });
         $.post('Backend/crearObservacion.php', data, function(response){
            try{
                let res = JSON.parse(response);
                Swal.close();
                Swal.fire(res.msg, '', 'success');
            }catch(error){
                Swal.close();
                Swal.fire(response, error, 'error')
            }
         });
            }else{
                Swal.fire('Operación cancelada', 'No se ha agregado la observación', 'error')
            }
        
        })
     })

})
