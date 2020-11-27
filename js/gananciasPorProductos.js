$(document).ready(function () {
    moment.locale('es')
	let inicio = moment().subtract(10, "days").format("YYYY-MM-D");
    let final = moment().format("YYYY-MM-D");
    let product = "todos";
	let plazo = "dias";
    cargarChart(inicio, final, plazo, product);
    cargarSelect();
    
    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
    }
    function cargarSelect(){
        $.get("Backend/select-productos.php", function(response){
            try{
                let res = JSON.parse(response);
                let template = `<option value="todos">Todos</option>`;
                res.forEach(producto =>{
                    template += `
                        <option value="${producto.id}">${producto.nombreProducto} </option>
                    `;
                })
                $('#producto').html(template);
            }catch(error){
                Swal.fire(response, '',error)
            }
        })
    }


	function cargarChart(fechaInicio, fechaFinal, duracion, producto) {
		const data = {
			fechaInicio,
			fechaFinal,
            duracion,
            producto
		};

		$.post("Backend/gananciasProductos.php", data, function (response) {
			try {
              
                
				let res = JSON.parse(response);
				console.log(res);
                let dataY = [];
                let dataEmpleada =[];
                let totalGanancias = [];
                let labelsX = [];
                let colors = [];
                let i = 0;
                let TotalCasa = 0;
                let TotalEmpleada = 0;
				res.forEach(element => {
                    dataY[i] = element.gananciaCasa;
                    dataEmpleada[i] = element.gananciaEmpleada
                    labelsX[i] = element.fecha;
                    totalGanancias[i] =parseFloat(element.gananciaCasa) + parseFloat(element.gananciaEmpleada);
                    TotalCasa += parseFloat(element.gananciaCasa);
                    TotalEmpleada += parseFloat(element.gananciaEmpleada);
                    colors[i] = random_rgba();
                    i++;
                });
                $('#myChart').remove();
                $('#chart').append(`<canvas id="myChart"></canvas>`)
                let template = `
                    <strong>Ganancia de casa: $${TotalCasa}</strong><br>
                    <strong>Ganancia de empleadas: $${TotalEmpleada}</strong> <br>
                    <strong>Ganancia de Total: $${TotalEmpleada+TotalCasa}</strong>
                `;
                $('#datos').html(template)
				var ctx = document.getElementById("myChart").getContext("2d");
				var myChart = new Chart(ctx, {
                    type: "bar",
                    
                    
                    data: 
                    {
						labels: labelsX,
						datasets: [
							{
                                fill:false,
								label: "Entradas Casa ($)",
                                data: dataY,
                                backgroundColor:'rgba(0,128,0,0.6)',
                                borderWidth: 5,
                                borderColor: 'rgba(0,128,0,0.6)'
                            },
							{
                                fill:false,
								label: "Entradas Empleadas($)",
                                data: dataEmpleada,
                                backgroundColor: 'rgba(128,0,124,0.6)',
                                borderWidth: 5,
                                borderColor: 'rgba(128,0,124,0.6)'
                            },
							{
                                fill:false,
								label: "Ganancia Total ($)",
                                data: totalGanancias,
                                backgroundColor: 'rgb(255,165,0, 0.3)',
                                borderWidth: 5,
                                borderColor: 'rgb(255,165,0, 0.6)'
                            },
                        
						],
					},
					options: {
                        
                        responsive: true,
                        
						scales: {
							yAxes: [
								{
									ticks: {
										beginAtZero: true,
									},
								},
							],
						},
					},
                });
                myChart.update();
			} catch (error) {
                Swal.fire(response, '', 'error')
				
			}
		});
    };
    
    //Datepicker
    $('#dateRange span').daterangepicker(
        {
           startDate: moment().subtract(10, 'days'),
           endDate: moment(),
           
           showDropdowns: true,
           showWeekNumbers: true,
           timePickerIncrement: 1,
           timePicker24Hour: true,
           
           opens: 'center',
           buttonClasses: ['btn btn-default'],
           applyClass: 'btn-small btn-primary',
           cancelClass: 'btn-small',
           dateFormat: 'DD-MMMM-YYYY:',
           timeFormat:  "hh:mm:ss",
           separator: ' Hasta ',
           minDate: moment().format('YYYY-MM-D'),
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
        inicio = start.format('YYYY-MM-D');
        final = end.format('YYYY-MM-D');    
 
        }
     );
     //Set the initial state of the picker label
     $('#dateRange span').html(moment().subtract(10, 'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
 

$('#graficar').click(function(){
    let plazo = $('#plazo').val();
    let producto = $('#producto').val();
    cargarChart(inicio, final, plazo, producto)
})

});
