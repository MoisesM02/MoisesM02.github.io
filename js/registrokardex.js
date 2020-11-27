$(document).ready(function(){
    moment.locale('es');
    let startDate = moment().startOf('day'); 
    let endDate = moment();
  
    
    

    cargarRegistros();
  
   
    
    // Llenar Select Empleadas
   
    
   
    
 
    //Agregar al registro
    $('#saveButton').on('click', function(){
        cargarRegistros();
    })
    
    
    
    
    //Cargar Tabla
    function cargarRegistros(){
        let fechaInicio = startDate.format('YYYY-MM-D HH:mm:ss');
        let fechaFinal = endDate.format('YYYY-MM-D HH:mm:ss');
        let direccion = "Backend/read-kardex.php";
    const data = {
       
        fechaInicio,
        fechaFinal
    };
    console.log(data);
    $.post(direccion,data, function(response){
      
        try{
        let res = JSON.parse(response)
        let entradas = (res)
        let template = "";
        template = `<center><table id='tablaDeDatos' style='cursor:pointer' class ='table table-striped table-bordered'>
        <thead class='thead-dark'>
        <tr>
        <th>ID</th>
        <th>ID de producto</th>
        <th>Proveedor</th>
        <th>No. Factura</th>
        <th>Tipo de operación</th>
        <th>Producto</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Valor promedio por unidad</th>
        <th>Entradas</th>
        <th>Valor de entradas</th>
        <th>Salidas</th>
        <th>Valor de salidas</th>
        <th>Inventario</th>
        <th>Valor promedio de inventario</th>
        <th>Usuario</th>
        </tr>
        </thead>
        <tbody>`;
       entradas.forEach(entrada => {
           template += `
            <tr>
            <td>${entrada.id}</td>
            <td>${entrada.idProducto}</td>
            <td>${entrada.proveedor}</td>
            <td>${entrada.numFactura}</td>
            <td>${entrada.tipoOperacion}</td>
            <td>${entrada.nombreProducto}</td>
            <td>${moment(entrada.fecha, 'YYYY-MM-DD HH:mm:ss').format('lll')}</td>
            <td>${entrada.descripcion}</td>
            <td>${entrada.valorUnitario}</td>
            <td>${entrada.cantidadEntradas}</td>
            <td>${entrada.valorEntradas}</td>
            <td>${entrada.cantidadSalidas}</td>
            <td>${entrada.valorSalidas}</td>
            <td>${entrada.cantidadTotal}</td>
            <td>${entrada.valorTotal}</td>
            <td>${entrada.usuario}</td>
            </tr>
           `
       });
      
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
                { extend: 'pdfHtml5', text: 'PDF', orientation: 'landscape', pageSize:'LEGAL'},
                { extend: 'print', text: 'Imprimir', autoPrint: true},
                'csv'
            ],
            lengthChange: true,
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
       Swal.fire(response, '', 'error');
    }
    })
    }
    
  
   
    
    
    
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
            cargarRegistros(inicio, final)
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