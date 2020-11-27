$(document).ready(function(){
moment.locale('es');
let startDate = moment().startOf('day'); 
let endDate = moment();
let initialDate =moment(); 
let finalDate = moment(); 
cargarRegistros(1,10,"Todos",startDate.format('YYYY-MM-D HH:mm:ss'),endDate.format('YYYY-MM-D HH:mm:ss'));
$('#reportrange span').html(startDate.format('D MMMM YYYY') + ' - ' + endDate.format('D MMMM YYYY'));


cargarSelect();
cargarSelectServicios();
cargarHabitaciones();
$('#disponibles').on('change', function(){
    cargarSelect();
})
$('#habitacionesDisponibles').on('change', function(){
    cargarHabitaciones();
})

function cargarHabitaciones(){
    let habitacionesDisponibles = $('#habitacionesDisponibles').prop('checked');
    console.log(habitacionesDisponibles)
    const data = (habitacionesDisponibles) ? {'now' : moment().format('YYYY-MM-D HH:mm:ss')} : {};
    $.post("Backend/select-habitaciones-disponibles.php", data, function(response){
        try {
            let habitaciones = JSON.parse(response);
            let template = ""
            habitaciones.forEach(habitacion =>{
                template += `<option value ="${habitacion.habitacion}">${habitacion.habitacion}</option>`;
            })
            $('#habitacion').html(template);
        } catch (error) {
            Swal.fire(response, '', 'error');
        }
    })
}


// Llenar Select Empleadas
function cargarSelect(){
    
    let disponibles = $('#disponibles').prop('checked');
    disponibles = (disponibles === false) ? false : true;
    let direccion = (disponibles) ? 'Backend/select-empleadas-disponibles.php' : 'Backend/select-all-empleadas.php';
    console.log(direccion);
    const data = (disponibles) ? {'now' : moment().format('YYYY-MM-D HH:mm:ss')} :{};
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

function cargarSelectServicios(){
    $.get("Backend/select-servicios.php",function(response){
        try{
        let servicios = JSON.parse(response);
        let template = `<option></option>`;
        servicios.forEach(servicio =>{
            template += `<option data-name="${servicio.nombreServicio}" value="${servicio.id}">${servicio.nombreServicio}</option>`
        })
        $('#servicioPrestado').html(template);
    }catch(e){
        Swal.fire(response, '', 'error');
    }
    })
}

$('#servicioPrestado').on('change', function(e){
    e.preventDefault();
    let id = $('#servicioPrestado').val();
    $.post("Backend/fill-services-form.php", {id}, function(response){
        try{
            let servicio = JSON.parse(response);
           
            $('#duracion').val(servicio[0].tiempo);
            $('#tipoServicio').val(servicio[0].tipo);
            $('#precioServicio').val(servicio[0].precioTotal);
            $('#gananciaCasa').val(servicio[0].gananciaCasa);
            $('#gananciaEmpleado').val(servicio[0].gananciaEmpleado);

        }catch(e){
            Swal.fire(response);
        }
    })
})
//Imprimir comprobante
$(document).on('click', '.imprimir', function(){
    let id = $(this).attr('data-id');
    let data = []
    let array = document.querySelectorAll(`.entrada${id}`)
    array.forEach(campo =>{
        data.push(campo.innerText);
    })
    $.post('Backend/crearComprobante.php', {data}, function(response){
        let url = window.location.href
		let partesUrl = url.split("/");
		let newUrl = "http:/";
		for(let i = 1; i<partesUrl.length-1; i++){
			
			newUrl += `${partesUrl[i]}/`
		}
		newUrl+="imprimirComprobante.php";
		
		window.open(newUrl, "_blank")
    })
})





//Cargar Tabla
function cargarRegistros(pageNumber, records, empleada, fechaInicio, fechaFinal){
    let direccion = "Backend/read-services-book.php";
const data = {
    pageNumber,
    records,
    empleada,
    fechaInicio,
    fechaFinal
};

$.post(direccion,data, function(response){
  
    try{
    let res = JSON.parse(response)
    let entradas = (res[0])
    let template = "";
    template = `<center><table id='tablaDeDatos' style='cursor:pointer' class ='table table-striped table-bordered'>
    <thead class='thead-dark'>
    <tr>
    <th>Comprobante</th>
    <th>Empleada</th>
    <th>Servicio prestado</th>
    <th>Tipo</th>
    <th>Usuario</th>
    <th>Precio de servicio</th>
    <th>Ganancia de casa</th>
    <th>Ganancia de empleada</th>
    <th>Descuentos por Limpieza</th>
    <th>Pago total a empleada</th>
    <th>Fecha y hora de inicio</th>
    <th>Fecha y hora de culminación</th>
    <th>Habitación</th>
    <th>Duración de servicio</th>
    <th>Eliminar</th>
    </tr>
    </thead>
    <tbody>`;
    let sumaEmpleada = 0;
    let sumaCasa = 0;
    let descuentosEmpleada = 0;
    let totalEmpleada = 0;
    let totalServicios = 0;
    entradas.forEach(entrada => {
        sumaEmpleada = sumaEmpleada + parseInt(entrada.gananciaEmpleada);
        descuentosEmpleada = descuentosEmpleada + parseInt(entrada.descuentosEmpleada)
        sumaCasa = sumaCasa + parseInt(entrada.gananciaCasa);
        totalEmpleada += parseInt(entrada.totalEmpleada);
        totalServicios += parseInt(entrada.precioFinal);
        template += `
        <tr>
        <td><a href="javascript:void(0)" data-id="${entrada.id}" class="imprimir">Imprimir </a> </td>
        <td class="entrada${entrada.id}" data-value="${entrada.nombre}">${entrada.nombre}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.servicioPrestado}">${entrada.servicioPrestado}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.tipo}">${entrada.tipo}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.usuario}">${entrada.usuario}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.precioFinal}">$${entrada.precioFinal}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.gananciaCasa}">$${entrada.gananciaCasa}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.gananciaEmpleada}">$${entrada.gananciaEmpleada}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.descuentosEmpleada}">$${entrada.descuentosEmpleada}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.totalEmpleada}">$${entrada.totalEmpleada}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.fechaInicio}">${entrada.fechaInicio}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.fechaFinal}">${entrada.fechaFinal}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.habitacion}">${entrada.habitacion}</td>
        <td class="entrada${entrada.id}" data-value="${entrada.tiempo}">${entrada.tiempo}</td>
        <td><a data-id="${entrada.id}" class="eliminar" href = "javascript:void(0)">Eliminar </a> </td>
        </tr>
        `;
    });
    if(res[1].nombreEmpleada != "Todos"){
        template += ` 
        <tfoot>
        <tr class ="table-primary">
        <td>Total de ganancia</td>
        <td></td>
        <td></td>
        <td></td>
        <td>$${totalServicios}</td>
        <td>$${res[2].gananciaCasa}</td>
        <td>$${res[2].gananciaEmpleada}</td>
        <td>$${res[2].descuentosEmpleada}</td>
        <td>$${res[2].GananciaTotalEmpleada}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        </tr>
        </tfoot>`;
    }
    template += `
    </tbody> </table></center>`
    
    
    $('#paginationData').html(template);
    
    $('#tablaDeDatos').DataTable({
        scrollY: true,
        dom: 'Blfrtip',

        lengthMenu:[10,25,50],
        buttons: [
            { extend: 'copyHtml5', text: 'Copiar' },
            { extend: 'excelHtml5', text: 'Excel' },
            { extend: 'pdfHtml5', text: 'PDF', orientation: 'landscape', pageSize: 'LEGAL' },
            { extend: 'print', text: 'Imprimir', autoPrint: true}
        ],
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
    console.error(error.message)
    $('#paginationData').html(response);
   
}
})
}

//Selección de página y entradas por página



//Eliminar un servicio
$(document).on('click', '.eliminar', function(){
    let id = $(this).attr('data-id');
    Swal.fire({
        title: "Borrar entrada",
        text: "¿Está seguro de querer borrar este registro?",
        icon: "warning",
        showConfirmButton: true,
        showDenyButton: true,
        allowEscapeKey: false,
        allowEnterKey: false,
        allowOutsideClick: false
    }).then((result) =>{
        if(result.isConfirmed){
            $.post('Backend/delete-libroDeServicios.php', {id}, function(response){
                try{
                    let res = JSON.parse(response)
                    Swal.fire(res.msg, "", "success");
                cargarRegistros(1,10,'Todos', startDate, endDate);
                }catch(error){
                    Swal.fire(response, "Es probable que el registro tenga más de 5 minutos de antigüedad", "error");
                }
                
            })
        }else{
            Swal.fire("Eliminación cancelada", "", "error");
        }
    })
    
})




// Datepicker
    $('#reportrange span').daterangepicker(
        {
           startDate: moment().startOf('day'),
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
           locale: {
               applyLabel: 'Confirmar',
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
     $('#reportrange span').html(moment().format('LL') + ' - ' + moment().format('D MMMM YYYY'));
 
     $('#saveButton').click(function(){

        let inicio = startDate.format('YYYY-MM-D HH:mm:ss')
        let final =  endDate.format('YYYY-MM-D HH:mm:ss')
        let empleada = $("#Empleada").val(); 
        let numerodeEntradas = $('#numOfRecords').val();
        let pages = $('#pageNumber').val();
        cargarRegistros(pages, numerodeEntradas, empleada, inicio, final)
     });



//Formulario para añadir
        $('#addService').click(function(e){
            e.preventDefault();
                let duracion =$('#duracion').val()
                let tipo = $('#tipoServicio').val()
                let username = $('#username').val()
                let habitacion = $('#habitacion').val()
                let descuentos = $('#descuentos').val()
                let nombreServicio = $('#servicioPrestado').find(':selected').attr('data-name')
                let nombreEmpleada = $('#nombreEmpleada').val()
                let precioServicio = $('#precioServicio').val()
                let gananciaCasa = $('#gananciaCasa').val()
                let gananciaEmpleado = $('#gananciaEmpleado').val()
                let fechaInicio = initialDate.format('YYYY-MM-D HH:mm:ss');
                let fechaFinal = finalDate.format('YYYY-MM-D HH:mm:ss');
                const data ={
                    duracion,
                    tipo,
                    username,
                    habitacion,
                    descuentos,
                    nombreServicio,
                    nombreEmpleada,
                    precioServicio,
                    gananciaCasa,
                    gananciaEmpleado,
                    fechaInicio,
                    fechaFinal
                };
                $('#addToRegisterBook').trigger('reset');
                $.post("Backend/create-services-book.php", data, function(response){
                    Swal.fire(response);                  
                    $('#habitacionesDisponibles').prop('checked', false).change();
                    cargarSelect();
                    cargarHabitaciones();
                    cargarRegistros(1,10,'Todos', startDate.format('YYYY-MM-DD HH:mm:ss'), endDate.format('YYYY-MM-DD HH:mm:ss'));
                    
                })
            })
    


// Deshabilitar los textbox
        $('#duracion').attr('disabled', 'disabled'); 
        $('#tipoServicio').attr('disabled', 'disabled'); 
        $('#precioServicio').attr('disabled', 'disabled'); 
        $('#gananciaCasa').attr('disabled', 'disabled'); 
        $('#gananciaEmpleado').attr('disabled', 'disabled'); 




        $('#showModal').click(function(e){
            e.preventDefault();
            edit = false;
            $('#formulario').modal("show")
        });


     $('#dateRange span').daterangepicker(
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
           minDate: moment().format('YYYY-MM-D HH:mm:ss'),
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
         $('#dateRange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        initialDate = start;
        finalDate = end;    
 
        }
     );
     //Set the initial state of the picker label
     $('#dateRange span').html(moment().subtract(0, 'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
  });
// })