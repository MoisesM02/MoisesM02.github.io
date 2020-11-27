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
    <title>Recibir productos</title>
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
    </script>
    <script src="js/recibos.js"></script>
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
                    <div class="card-body">
                        <hr>
                        <div class="form-group">
                            <div class="form-group">
                            <label for="numFactura"><strong>No. Factura</strong></label>
                                <input type="text" id="numFactura" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="proveedor"><strong>Proveedor</strong></label>
                        <input type="text" id="proveedor" class="form-control">
                        </div>
                        <span class="d-inline"><strong>Total factura: $</strong></span><div class="d-inline" id="totalFactura"></div>
                        <div class="form-group">
                        <select class="form-control" id="tipoPago">
                        <option>Tarjeta</option>
                        <option>Efectivo</option>
                         </select>
                         </div>
                        <hr>
                        <button class="btn btn-primary" id="enviarFactura">Terminar</button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>