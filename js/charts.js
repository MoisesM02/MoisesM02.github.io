$(document).ready(function () {
    moment.locale('es')
	let inicio = moment().subtract(10, "days").format("YYYY-MM-D");
	let final = moment().format("YYYY-MM-D");
	let plazo = "dias";
    cargarChart(inicio, final, plazo);
    
    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
    }

    $('#PDF').on('click', function(){
        saveAsPDF();
    })
    function saveAsPDF() {
        html2canvas(document.getElementById("myChart")).then(function(canvas){
            let img = canvas.toDataURL()
            let doc = new jsPDF('l');
            doc.addImage(img, 10,10);
            doc.save('gananciasServicios.pdf');
        })
     }

	function cargarChart(fechaInicio, fechaFinal, duracion) {
		const data = {
			fechaInicio,
			fechaFinal,
			duracion,
		};

		$.post("Backend/obtain-data.php", data, function (response) {
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
                    totalGanancias[i] =parseInt(element.gananciaCasa) + parseInt(element.gananciaEmpleada);
                    TotalCasa += parseInt(element.gananciaCasa);
                    TotalEmpleada += parseInt(element.gananciaEmpleada);
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
								label: "Entradas Empleada ($)",
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
                console.log(error.message);
                console.log(response);
				
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
    cargarChart(inicio, final, plazo)
})

});
