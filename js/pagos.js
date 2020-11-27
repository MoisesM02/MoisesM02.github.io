$(function(){
    let startDate = moment().startOf('day'); 
    let endDate = moment();
    let initialDate =moment(); 
    let finalDate = moment(); 

    //Cargas iniciales
    cargarSelect();
    
    cargarRegistros('Todos', startDate.format('YYYY-MM-DD HH:mm:ss'), endDate.format('YYYY-MM-DD HH:mm:ss'));
    //CargarSelect de empleadasfunction cargarSelect(){
    function cargarSelect(){
    let direccion ='Backend/select-all-empleadas.php';
    
    const data = {'now' : moment().format('YYYY-MM-D HH:mm:ss')};
    $.post(direccion, data, function(response){
        try{
            let res = JSON.parse(response);
            let template;
            if(direccion == 'Backend/select-all-empleadas.php'){
            template = "<option value='Todos'>Todas</option>";
            }
            
            res.forEach(empleada => {
                template += `
                <option value = "${empleada.nombreEmpleada}">${empleada.nombreEmpleada} </option>
                `;
            })
            $('.Empleadas').html(template)   
        }catch(e){
            Swal.fire(response, '', 'error');
        }
    })
} 


    
    //Permitir solo números para el pago
    var inputPago = new Cleave('#montoPagar',{
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'   
    })
    
    var inputDescuentos = new Cleave('#descuentos',{
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'   
    })
    //Eliminar
    $(document).on('click', '.eliminar', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        Swal.fire({
            title: "Confirmar pago",
            text: "¿Desea confirmar el pago?",
            icon: "question",
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Por favor espere",
                    html: `
                    Cargando... <br> <br>
                    <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    `  
                 })
                 $.post('Backend/eliminarPago.php', {id}, function(response){
                    try{
                        let res = JSON.parse(response);
                        let inicio = startDate.format('YYYY-MM-D HH:mm:ss')
                        let final =  endDate.format('YYYY-MM-D HH:mm:ss')
                        let empleada = $("#Empleada").val(); 
                        cargarRegistros(empleada, inicio, final)
                        Swal.close();
                        Swal.fire(res.msg, '', res.icon);
                    }catch(error){
                        
                    }
                 })
            }else{
                Swal.fire('Cancelado', 'Eliminación cancelada', 'error')
            }
        })

    })

    //Añadir pago
    $('#addPayment').on('click', function(){

        let pago = $('#montoPagar').val();
        let descuentos = $('#descuentos').val();
        let total = $('#total').val();
        let usuario = $('#username').val();
        let empleado = $('#selectEmpleadas').val();
        const data = {
            pago,
            descuentos,
            total,
            usuario,
            empleado
        };
        Swal.fire({
            title: "Confirmar pago",
            text: "¿Desea confirmar el pago?",
            icon: "question",
            showConfirmButton: true,
            showDenyButton: true,
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Por favor espere",
                    html: `
                    Cargando... <br> <br>
                    <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                    </div>
                    `  
                 })
                 $.post("Backend/pagar.php", data, function(response){
                    try{
                        let res = JSON.parse(response);
                        Swal.close();
                        Swal.fire(res.msg, '', 'success')
                        let inicio = startDate.format('YYYY-MM-DD HH:mm:ss');
                        let final = moment().format('YYYY-MM-DD HH:mm:ss');
                        let empleada = $('#Empleada').val()
                        cargarRegistros(empleada, inicio, final);
                    }catch(error){
                        Swal.close();
                        Swal.fire(response, '', 'error')
                    }
                 })
            }
        })
    })



    //Asignar el total automáticamente
    $('#montoPagar').on('change', function(){
        let montoAPagar = parseInt($('#montoPagar').val());
        let descuentos = parseInt($('#descuentos').val());
        let total = montoAPagar-descuentos;
        if(total<0){
            Swal.fire('Error', 'Los descuentos no pueden superar al pago', 'error');
            $('#descuentos').val(0);
            $('#montoPagar').val(0);
            $('#total').val(0);
        }else{
            $('#total').val(total);
        }
    })
    $('#descuentos').on('change', function(){
        let montoAPagar = parseInt($('#montoPagar').val());
        let descuentos = parseInt($('#descuentos').val());
        let total = montoAPagar-descuentos;
        if(total<0){
            Swal.fire('Error', 'Los descuentos no pueden superar al pago', 'error');
            $('#descuentos').val(0);
            $('#montoPagar').val(0);
            $('#total').val(0);
        }else{
            $('#total').val(total);
        }
    })

    //Función para cargar registros
    function cargarRegistros(empleada, inicio, final){
        const data ={
            empleada,
            inicio,
            final
        }
        $.post('Backend/fetch-pagos.php', data, function(response){
            try{
                let res = JSON.parse(response);
                let template = `<center><table id='tablaDeDatos' style='cursor:pointer' class ='table table-striped table-bordered'>
                <thead class='thead-dark'>
                <tr>
                <th>ID de pago</th>
                <th>Empleada</th>
                <th>Pago</th>
                <th>Descuentos</th>
                <th>Total</th>
                <th>Fecha de pago</th>
                <th>Usuario</th>
                <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>`;
                res.forEach(entrada =>{
                    template +=`
                        <tr>
                        <td>${entrada.idPago}</td>
                        <td>${entrada.nombreEmpleada}</td>
                        <td>${entrada.pago}</td>
                        <td>${entrada.descuento}</td>
                        <td>${entrada.total}</td>
                        <td>${entrada.fecha}</td>
                        <td>${entrada.usuario}</td>
                        <td><a data-id="${entrada.idPago}" class="eliminar" href = "javascript:void(0)">Eliminar</a></td>
                        </tr>
                    `;
                })


                template += `</tbody> </table></center>`
                $('#paginationData').html(template);
                $('#tablaDeDatos').DataTable({
                    scrollY: true,
                    
                    language: {
                        infoFiltered:   "(filtrado de _MAX_  entradas totales)",
                        zeroRecords:    "No se han encontrado registros que concuerden",
                        emptyTable:     "La tabla está vacía",
                        infoEmpty:  "No se han encontrado registros que concuerden",
                        search: "Buscar:",
                        paginate:{
                            first: "Primera",
                            last: "Última",
                            previous: "Anterior",
                            next: "Siguiente"
                        },
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        lengthMenu: "Mostrar _MENU_ entradas"
                    }
                });
            }catch(error){
                Swal.fire(response, '', 'error')
            }
        })
    }




    //Buscar registros
    $('#saveButton').click(function(){

        let inicio = startDate.format('YYYY-MM-D HH:mm:ss')
        let final =  endDate.format('YYYY-MM-D HH:mm:ss')
        let empleada = $("#Empleada").val(); 
        cargarRegistros(empleada, inicio, final)
     });


    $('#showModal').click(function(e){
        e.preventDefault();
        edit = false;
        $('#formulario').modal("show")
    });



//daterange picker
 $('#reportrange span').daterangepicker(
    {
       startDate: moment(),
       endDate: moment(),
       
       showDropdowns: true,
       showWeekNumbers: true,
       timePicker: true,
       timePickerIncrement: 1,
       timePicker24Hour: true,
       
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
     $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
    startDate = start;
    endDate = end;    

    }
 );
 //Set the initial state of the picker label
 $('#reportrange span').html(moment().subtract(0, 'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));


 

})