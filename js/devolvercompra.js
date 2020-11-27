$(function(){

    $('#buscar').prop('disabled', true);

	$('#fijarFactura').on('click', function(){
        $('#buscar').prop('disabled', false);
		$('#cliente').prop('disabled', true);
		$('#numFactura').prop('disabled', true);
		$('#fijarFactura').prop('disabled', true);
	})
	$('#reiniciarPagina').on('click', function(){
		location.reload(true);
	})


      

    $("#buscadorProductos").on("input", function () {
		let parametro = document.getElementById("buscadorProductos").value;

		$.post("Backend/search-products.php", { parametro }, function (response) {
			try {
				let res = JSON.parse(response);
				let nombres = [];
				res[0].forEach((producto) => {
					nombres.push([producto.nombre, producto.id]);
				});

				var objInput = document.getElementById("buscadorProductos");
				var strInput = objInput.value;

				var objSuggestionsDiv = document.getElementById("sugerencias");
				if (strInput.length > 0) {
					objSuggestionsDiv.innerHTML = "";
					var objList = document.createElement("ul");

					for (var i = 0; i < nombres.length; i++) {
						var word = nombres[i][0];
						var objListEntity = document.createElement("option");
						objListEntity.setAttribute(
							"onclick",
							`complete('${word}', 'buscadorProductos', 'sugerencias', ${nombres[i][1]} );`
						);
						objListEntity.innerHTML = word;
						objList.appendChild(objListEntity);
					}
					objSuggestionsDiv.appendChild(objList);
				} else {
					objSuggestionsDiv.innerHTML = "";
				}
			} catch (error) {
				alert(error.name + " " + error.message);
			}
		});
	});



 
    $("#buscar").on("click", function () {
        let id = $("#idProducto").val();
        let cliente = $('#cliente').val();
        let numFactura = $('#numFactura').val();
        const data ={
            id,
            numFactura
		}
		console.log(data)
		if (id != null || id !== undefined || !id) {
			$.post("Backend/seleccionar-compra-devolucion.php", data, function (response) {
				try {
                    let res = JSON.parse(response);
                    console.log(res);
                    let template = $("#listaProductos").html();
					let precio = res[0].precioProducto;
					let cliente = res[0].proveedor
                    
					if (template == undefined || template == "" || template == null || template.includes("<th>") !== true) {
						template += `
                        <tr>
                        <th>Borrar</th>
                        <th>Nombre del producto</th>
                        <th>Costo</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        </tr>
                        <tr id="remove${res[0].idProducto}"> 
                        <td><a data-id="${res[0].idProducto}" class="borrar" href="javascript:void(0)">Borrar</a></td>
                        <td><strong><center>${res[0].nombreProducto}</center></strong></td>
                        <td><input data-id = "${res[0].idProducto}"  data-producto="${res[0].nombreProducto}" class="campo form-control" id="precioCompra${res[0].idProducto}" type="number" value="${precio}" readonly/></td>
                        <td><input data-id = "${res[0].idProducto}"  data-producto="${res[0].nombreProducto}" class="campo form-control" id="cantidad${res[0].idProducto}" type="number" min="1" max="${res[0].cantidadTotal}" value="${res[0].cantidadTotal}"/></td>
                       	<td><span id="total${res[0].idProducto}">${precio * res[0].cantidadTotal}</span></td>
                        </tr>
                        `;
						let nuevoTotalFactura = res[0].cantidadTotal * precio;
						$("#totalFactura").text(nuevoTotalFactura);
						$('#cliente').val(cliente);
					} else {
						if(cliente == $('#cliente').val())
						{
						template += `
                        
                        <tr id="remove${res[0].idProducto}"> 
                        <td><a data-id="${res[0].idProducto}"class="borrar" href="javascript:void(0)">Borrar</a></td>
                        <td><strong>${res[0].nombreProducto}</strong></td>
                        <td><input data-id = "${res[0].idProducto}" data-producto="${res[0].nombreProducto}" class="campo form-control" id="precioCompra${res[0].idProducto}" type="number" value="${precio}" readonly/></td>
                        <td><input data-id = "${res[0].idProducto}" data-producto="${res[0].nombreProducto}" class="campo form-control" id="cantidad${res[0].idProducto}" type="number" min="1" max="${res[0].cantidadTotal}" value="${res[0].cantidadTotal}"/></td>
                        <td><span id="total${res[0].idProducto}">${precio * res[0].cantidadTotal}</span></td>
                        </tr>
                        `;
						let totalFactura = $("#totalFactura").text();
						let nuevoTotalFactura = parseFloat(totalFactura) + res[0].cantidadTotal * precio;
						$("#totalFactura").text(nuevoTotalFactura);
						}else{
							Swal.fire("El cliente no concuerda, por favor revise el número de factura y producto");
						}
					}
					$("#idProducto").val("");
					$("#buscadorProductos").val("");
					$("#listaProductos").html(template);
				} catch (error) {
					Swal.fire(response);
				}
			});
		}
	});
	//Eliminar productos de la tabla
	$(document).on("click", ".borrar", function (e) {
		e.preventDefault();
		let id = $(this).attr("data-id");
		let precioTotalProductos = $(`#total${id}`).text();
		let precioTotalFactura = $("#totalFactura").text();
		let nuevoPrecioFactura = precioTotalFactura - precioTotalProductos;
		$("#totalFactura").text(nuevoPrecioFactura);
		$(`#remove${id}`).remove();
	});

	//Cambiar valores de cada producto y el total de la factura
	$(document).on("change", ".campo", function () {
		let id = $(this).attr("data-id");
		let campo = $(this).attr("id");
		if (campo.includes("precioCompra")) {
			let precio = $(this).val();
			let cantidad = $(`#cantidad${id}`).val();
			let antiguoTotal = $(`#total${id}`).text();
			let nuevoPrecioProducto = precio * cantidad;
			let precioTotal = $("#totalFactura").text();
			let nuevoPrecioTotal = parseFloat(precioTotal) - antiguoTotal + nuevoPrecioProducto;
			$(this).attr("value", precio);
			$(`#cantidad${id}`).attr("value", cantidad);
		
			$(`#total${id}`).text(nuevoPrecioProducto);
			$("#totalFactura").text(nuevoPrecioTotal);
		} else if (campo.includes("cantidad")) {
			let cantidad = $(this).val();
			let precio = $(`#precioCompra${id}`).val();
			let antiguoTotal = $(`#total${id}`).text();
			let nuevoPrecioProducto =precio * cantidad 
			let precioTotal = $("#totalFactura").text();
			let nuevoPrecioTotal =parseFloat(precioTotal) - antiguoTotal + nuevoPrecioProducto;
			console.log(nuevoPrecioTotal);
			$(this).attr("value", cantidad);
			$(`#precioCompra${id}`).attr("value", precio);
			$(`#total${id}`).text(nuevoPrecioProducto);
			$("#totalFactura").text(nuevoPrecioTotal);
		}
	});


	$("#enviarFactura").on("click", function () {
		
		let a = $("tr").find("input.campo");
		let aArray = Object.keys(a).map((key) => [Number(key), a[key]]);
		let i = 1;
		let array = [];
		let j = 0;
		aArray.forEach((input) => {
			if (!isNaN(input[0])) {
				if (i <= 2) {
					if (typeof array[j] == "undefined") {
						array.push([]);
						array[j].push([
							$(`#${input[1].id}`).attr("data-producto"),
							$(`#${input[1].id}`).val(),
							$(`#${input[1].id}`).attr("data-id"),
							$("#username").val(),
							$("#cliente").val(),
							$("#numFactura").val(),
							
						]);
						if (i == 2) {
							j = 0;
							i = 1;
						} else {
							i++;
						
						}
					} else {
						array[j].push([
							$(`#${input[1].id}`).attr("data-producto"),
							$(`#${input[1].id}`).val(),
							$(`#${input[1].id}`).attr("data-id"),
							$("#username").val(),
							$("#cliente").val(),
							$("#numFactura").val(),
						]);

						if (i == 2) {
							j++;
							i = 1;
						} else {
							i++;
						}
					}
				} else {
					j++;
					if (j == 1) {
						j = 0;
					}
				}
			}
		});
		
			Swal.fire({
				title: "Confirmar devolución",
				text: "¿Desea confirmar la devolución de la compra?",
				icon: "warning",
				showConfirmButton: true,
				showDenyButton: true,
				allowEscapeKey: false,
				allowEnterKey: false,
				allowOutsideClick: false,
			}).then((result) => {
				if (result.isConfirmed) {
					$.post("Backend/devolvercompra.php", { array }, function (response) {
						Swal.fire(response);
					});
				} else {
					Swal.fire("Devolución cancelada", "", "error");
				}
			});
		
	});

})