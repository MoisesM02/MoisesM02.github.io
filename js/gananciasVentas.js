$(document).ready(function(){
    moment.locale('es');
    let startDate = moment().startOf('day'); 
    let endDate = moment();
    let initialDate =moment(); 
    let finalDate = moment(); 
    
    
    cargarSelect();
    cargarRegistros();
  
    $('#disponibles').on('change', function(){
        cargarSelect();
    })
    
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
                template = `
                <option value='todos' selected='selected'>Todos</option>
                <option value='general'>General</option>`;
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
    
   
    
 
    //Agregar al registro
    $('#saveButton').on('click', function(){
        cargarRegistros();
    })
    
    
    
    
    //Cargar Tabla
    function cargarRegistros(){
        let fechaInicio = startDate.format('YYYY-MM-D HH:mm:ss');
        let fechaFinal = endDate.format('YYYY-MM-D HH:mm:ss');
        let empleada =($('#Empleada').val() != null) ? $('#Empleada').val(): 'todos'; 
        let direccion = "Backend/read-products-sales.php";
    const data = {
        empleada,
        fechaInicio,
        fechaFinal
    };
    console.log(data);
    $.post(direccion,data, function(response){
      
        try{
        let res = JSON.parse(response)
        let entradas = (res[0])
        let template = "";
        template = `<center><table id='tablaDeDatos' style='cursor:pointer' class ='table table-striped table-bordered'>
        <thead class='thead-dark'>
        <tr>
        <th>ID de venta</th>
        <th>Numero de Factura</th>
        <th>Producto vendido</th>
        <th>Cantidad vendida</th>
        <th>Cliente</th>
        <th>Ganancia Total</th>
        <th>Ganancia de casa</th>
        <th>Ganancia de empleada</th>
        <th>Fecha de venta</th>
        <th>Forma de pago</th>
        <th>Vendedor</th>
        </tr>
        </thead>
        <tbody>`;
        let sumaEmpleada = 0;
        let sumaCasa = 0;
        let descuentosEmpleada = 0;
        let totalEmpleada = 0;
        let totalVentas = 0;
        entradas.forEach(entrada => {
            sumaEmpleada = sumaEmpleada + parseInt(entrada.gananciaEmpleada);
            sumaCasa = sumaCasa + parseInt(entrada.gananciaCasa);
            totalVentas += parseInt(entrada.precioTotal);
            template += `
            <tr>
            <td>${entrada.idVenta}</td>
            <td>${entrada.numFactura}</td>
            <td>${entrada.nombreProducto}</td>
            <td>${entrada.cantidad}</td>
            <td>${entrada.nombreCliente}</td>
            <td>$${entrada.precioTotal}</td>
            <td>$${entrada.gananciaCasa}</td>
            <td>$${entrada.gananciaEmpleada}</td>
            <td>${entrada.fechaVenta}</td>
            <td>${entrada.formaPago}</td>
            <td>${entrada.usuario}</td>
            </tr>
            `;
        });
        if(res[1].Cliente != "todos"){
            template += ` 
            <tfoot>
            <tr class ="table-primary">
            <td>Total de ganancia</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>$${res[1].GananciaTotal}</td>
            <td>$${res[1].gananciaCasa}</td>
            <td>$${res[1].gananciaEmpleada}</td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tfoot>`
            ;
        }
        template += `
        </tbody> </table></center>`
        let pagination = "";
        pagination = `
        <center>
        <nav aria-label='Page navigation example'>
        <ul class ='pagination'> 
        `;
        let numofPages = res[1].numPaginas;
        for(let i = 1; i<=numofPages; i++){
            pagination += `<li class='page-item pagination-link' data-page='${i}'> <a class='page-link pagination-link' data-page='${i}'  href='javascript:void(0)'> ${i} </a> </li>`
        }
        pagination += `
        </ul>
        </nav>
        </center> 
        `;
        
        $('#paginationData').html(template);
        $('#pages').html(pagination);
        $('#tablaDeDatos').DataTable({
            scrollY: true,
            dom: 'Blfrtip',
    
            lengthMenu:[10,25,50],
            buttons: [
                { extend: 'copyHtml5', text: 'Copiar' },
                { extend: 'excelHtml5', text: 'Excel' },
                { extend: 'pdfHtml5', text: 'PDF', orientation: 'landscape' },
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
        $('#paginationData').html(response);
        console.error(error);
    }
    })
    }
    
    //Selección de página y entradas por página
  
    
    //Eliminar un servicio
   
    
    
    
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
           
        
    
    
    // Deshabilitar los textbox
           
    
    
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