$(function () {
	cargarSelectClientes();

	var pago = new Cleave('#pago', {
		numeral: true,
		numeralThousandsGroupStyle: 'none'
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

	$('#buscar').prop('disabled', true);
	$('#imprimir').prop('disabled', true);
	
	$('#imprimir').on("click", function(){
		let url = window.location.href
		let partesUrl = url.split("/");
		let newUrl = "http:/";
		for(let i = 1; i<partesUrl.length-1; i++){
			
			newUrl += `${partesUrl[i]}/`
		}
		newUrl+="imprimirTicketVenta.php";
		
		window.open(newUrl, "_blank")
	})

	$('#fijarCliente').on('click', function(){
		$('#buscar').prop('disabled', false);
		$('#cliente').prop('disabled', true);
		$('#fijarCliente').prop('disabled', true);
		
	})
	$('#reiniciarPagina').on('click', function(){
		location.reload(true);
	})

	$("#buscar").on("click", function () {
        let id = $("#idProducto").val();
        let cliente = $('#cliente').val();
        
		if (id != null || id !== undefined || !id) {
			$.post("Backend/select-single-product.php", {id}, function (response) {
				try {
					let res = JSON.parse(response);
                    let template = $("#listaProductos").html();
                    let precio = 0;
                    if(cliente == "general"){
                        precio = res[0].precioCliente
                    }else{
                        precio = res[0].precioEmpleado
                    }
					if (template == undefined || template == "" || template == null || template.includes("<th>") !== true) {
						template += `
                        <tr>
                        <th>Borrar</th>
                        <th>Nombre del producto</th>
                        <th>Costo</th>
                        <th>Cantidad</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        </tr>
                        <tr id="remove${res[0].id}"> 
                        <td><a data-id="${res[0].id}" class="borrar" href="javascript:void(0)">Borrar</a></td>
                        <td><strong>${res[0].nombre}</strong>  Cantidad en inventario: <strong>${res[0].cantidad}</strong></td>
                        <td><input data-id = "${res[0].id}"  data-producto="${res[0].nombre}" class="campo form-control" id="precioCompra${res[0].id}" type="number" value="${precio}" readonly/></td>
                        <td><input data-id = "${res[0].id}"  data-producto="${res[0].nombre}" class="campo form-control" id="cantidad${res[0].id}" type="number" min="0" value="${res[0].cantidad}"/></td>
                        <td><input data-id = "${res[0].id}"  data-producto="${res[0].nombre}" class="campo form-control" id="descuento${res[0].id}" type="number" min="0" value="0"/> </td>
                        <td><span id="total${res[0].id}">${precio * res[0].cantidad}</span></td>
                        </tr>
                        `;
						let nuevoTotalFactura = res[0].cantidad * precio;
						$("#totalFactura").text(nuevoTotalFactura);
					} else {
						template += `
                        
                        <tr id="remove${res[0].id}"> 
                        <td><a data-id="${res[0].id}"class="borrar" href="javascript:void(0)">Borrar</a></td>
                        <td><strong>${res[0].nombre}</strong> Cantidad en inventario: <strong>${res[0].cantidad}</strong></td>
                        <td><input data-id = "${res[0].id}" data-producto="${res[0].nombre}" class="campo form-control" id="precioCompra${res[0].id}" type="number" value="${precio}" readonly/></td>
                        <td><input data-id = "${res[0].id}" data-producto="${res[0].nombre}" class="campo form-control" id="cantidad${res[0].id}" type="number" value="${res[0].cantidad}"/></td>
                        <td><input data-id = "${res[0].id}" data-producto="${res[0].nombre}" class="campo form-control" id="descuento${res[0].id}" type="number" value="0"/> </td>
                        <td><span id="total${res[0].id}">${precio * res[0].cantidad}</span></td>
                        </tr>
                        `;
						let totalFactura = $("#totalFactura").text();
						let nuevoTotalFactura = parseFloat(totalFactura) + res[0].cantidad * precio;
						$("#totalFactura").text(nuevoTotalFactura);
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
			let descuento = $(`#descuento${id}`).val();
			let cantidad = $(`#cantidad${id}`).val();
			let antiguoTotal = $(`#total${id}`).text();
			let nuevoPrecioProducto =
				precio * cantidad - precio * cantidad * (descuento / 100);
			let precioTotal = $("#totalFactura").text();
			let nuevoPrecioTotal =
				parseFloat(precioTotal) - antiguoTotal + nuevoPrecioProducto;
			
			$(this).attr("value", precio);
			$(`#cantidad${id}`).attr("value", cantidad);
			$(`#descuento${id}`).attr("value", descuento);
			$(`#total${id}`).text(nuevoPrecioProducto);
			$("#totalFactura").text(nuevoPrecioTotal);
		} else if (campo.includes("cantidad")) {
			let cantidad = $(this).val();
			let precio = $(`#precioCompra${id}`).val();
			let descuento = $(`#descuento${id}`).val();
			let antiguoTotal = $(`#total${id}`).text();
			let nuevoPrecioProducto =
				precio * cantidad - precio * cantidad * (descuento / 100);
			let precioTotal = $("#totalFactura").text();
			let nuevoPrecioTotal =
				parseFloat(precioTotal) - antiguoTotal + nuevoPrecioProducto;
			
			$(this).attr("value", cantidad);
			$(`#precioCompra${id}`).attr("value", precio);
			$(`#descuento${id}`).attr("value", descuento);
			$(`#total${id}`).text(nuevoPrecioProducto);
			$("#totalFactura").text(nuevoPrecioTotal);
		} else if (campo.includes("descuento")) {
			let descuento = $(this).val();
			let precio = $(`#precioCompra${id}`).val();
			let cantidad = $(`#cantidad${id}`).val();
			let antiguoTotal = $(`#total${id}`).text();
			let nuevoPrecioProducto = precio * cantidad - precio * cantidad * (descuento / 100);
			let precioTotal = $("#totalFactura").text();
			let nuevoPrecioTotal =
				parseFloat(precioTotal) - antiguoTotal + nuevoPrecioProducto;
			
			$(this).attr("value", descuento);
			$(`#precioCompra${id}`).attr("value", precio);
			$(`#cantidad${id}`).attr("value", cantidad);
			$(`#total${id}`).text(nuevoPrecioProducto);
			$("#totalFactura").text(nuevoPrecioTotal);
		}
	});

	//Llenar select de clientes
	function cargarSelectClientes() {
		$.get("Backend/select-all-empleadas.php", function (response) {
			try {
				let empleadas = JSON.parse(response);
				let template = `<option value="general">General</option>`;
				empleadas.forEach((empleada) => {
					template += `
                        <option value="${empleada.nombreEmpleada}">${empleada.nombreEmpleada} </option>
                    `;
				});
				$("#cliente").html(template);
			} catch (error) {
				Swal.fire(response);
			}
		});
	}

	// Modificar ruta
	$("#enviarFactura").on("click", function () {
		let prop = $('#fijarCliente').prop('disabled')
		let totalFactura = parseFloat($('#totalFactura').text());
		let pago = parseFloat($('#pago').val());
		let cambio = pago-totalFactura;
		
		let a = $("tr").find("input.campo");
		let aArray = Object.keys(a).map((key) => [Number(key), a[key]]);
		let i = 1;
		let array = [];
		let j = 0;
		aArray.forEach((input) => {
			if (!isNaN(input[0])) {
				if (i <= 3) {
					if (typeof array[j] == "undefined") {
						array.push([]);
						array[j].push([
							$(`#${input[1].id}`).attr("data-producto"),
							$(`#${input[1].id}`).val(),
							$(`#${input[1].id}`).attr("data-id"),
							$("#username").val(),
							$("#cliente").val(),
							$("#numFactura").val(),
							$("#tipoPago").val(),
							$('#pago').val()
						]);
						if (i == 3) {
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
							$("#tipoPago").val(),
							$('#pago').val()
						]);

						if (i == 3) {
							j++;
							i = 1;
						} else {
							i++;
						}
					}
				} else {
					j++;
					if (j == 2) {
						j = 0;
					}
				}
			}
		});
		if(prop && (!isNaN(cambio) && cambio >=0)){
			Swal.fire({
				title: "Confirmar compra",
				text: "¿Desea confirmar la compra?",
				icon: "info",
				showConfirmButton: true,
				showDenyButton: true,
				allowEscapeKey: false,
				allowEnterKey: false,
				allowOutsideClick: false,
			}).then((result) => {
				if (result.isConfirmed) {
					$.post("Backend/vender.php", { array }, function (response) {
						try {
							let res = JSON.parse(response);
							Swal.fire(res.msg, '', 'success');
							$('#imprimir').prop('disabled', false);
						} catch (error) {
							Swal.fire(response, '', 'error');
						}
					});
				} else {
					Swal.fire("Venta cancelada", "", "error");
				}
			});
		}else{
		Swal.fire("Debes fijar un cliente primero e ingresa un pago mayor o igual al valor de la venta", '', "error")
		}

		
	});
});
