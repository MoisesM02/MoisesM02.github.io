<?php
session_start();
function usernameValue(){
if(!isset($_SESSION["username"]) && empty($_SESSION["username"])){
    header("Location:login.php");
}else{
    echo utf8_encode($_SESSION["username"]);
}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/recibosdeproductos.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert2.min.js"></script>
    <script src="js/cleave.min.js"></script>
    <script>
        function complete(strComplete, strInputId, strSuggestionsDiv, id){
        document.getElementById(strInputId).value = strComplete;
        document.getElementById(strSuggestionsDiv).innerHTML = "";
        document.getElementById('idProducto').value = id;  
    }
    function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
    </script>
    <script src="js/vender.js"></script>
</head>
<body>
<input type="hidden" id="username" value="<?php usernameValue();?>">
<?php include('includes/menu.php');?>
    <input type="hidden" id="idProducto">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="buscador mb-3">
                    <div class="row ">
                        <div class="col-md-8">
                        <div class="form-group">
                        <input type="text" class="form-control" id="buscadorProductos"/>
                        <div class="sugerencias" id="sugerencias"></div> 
                        </div> <!-- -->
                        </div>
                        <div class="col-md-4">
                        <button class="btn btn-primary" id="buscar">Añadir</button>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                <table class="table table-striped table-bordered" id="listaProductos">
                </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                <div class="card-header">
                <label for="numFactura">
                    Número de factura
                </label>
                <input type="text" id="numFactura" onkeypress="return isNumberKey(event)" class="form-control">
                </div>
                    <div class="card-body">
                    <span>Total factura:</span><div class="" id="totalFactura"></div>
                        <hr>
                        <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <select id="cliente" class="form-control" ></select>
                        <button class="btn btn-primary mt-3" id="fijarCliente">Fijar cliente</button>
                        <button class="btn btn-danger mt-3" id="reiniciarPagina">Reiniciar página</button>
                        </div>
                        <hr>
                        <div class="form-group">
                        <input type="text" placeholder="Cantidad pagada por el usuario" id="pago" class="form-control"><br>
                        <select class="form-control" id="tipoPago">
                        <option>Tarjeta</option>
                        <option>Efectivo</option>
                        </select>
                        </div>
                        <hr>
                        <button class="btn btn-primary" id="enviarFactura">Terminar</button>
                        <button class="btn btn-success" id="imprimir">Imprimir ticket</button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>