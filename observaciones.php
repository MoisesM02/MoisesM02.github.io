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
    <title>Observaciones</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="css/daterangepicker.css">

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/cleave.min.js"></script>
    <script src="js/sweetalert2.min.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <script src="js/daterangepicker.js"></script>
    <script src="js/observaciones.js"></script>
    
</head>
<style>
.img{
    width: 90%;
    height: 60vh;
}
body, .container{
    max-width: 100vw;
}
</style>
<body>
<input type="hidden" value="<?php usernameValue() ?>" id="username">
<?php include('includes/menu.php');?>
<div class="container">
    <div class="row mt-3">
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" id="buscador" placeholder="Número de DUI" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" id="buscar">Buscar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div id="info">
                <div class="card mt-2" id="form">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                               <div class="foto">
                               </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="nombres"><strong>Nombres</strong></label>
                                    <input type="text" id="nombres" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="apellidos"><strong>Apellidos</strong></label>
                                    <input type="text" name="" id="apellidos" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="apellidos"><strong>DUI</strong></label>
                                    <input type="text" name="" id="DUI" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="estado"><strong>Estado</strong></label>
                                    <select name="" class="form-control" id="estado"></select>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                            <div class="form-group">
                            <label for="fecha"><strong>Fecha</strong></label>
                            <input type="text" id="fecha" class="form-control" readonly>
                            </div>
                            <label><strong>Cambiar fecha</strong></label>
                                <div id="dateRange" class="my-3" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 80%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="residencia"><strong>Lugar de residencia</strong></label>
                                    <input type="text" id="residencia" class="form-control">
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="edad"><strong>Edad</strong></label>
                                    <input type="text" id="edad" class="form-control mt-3" readonly>
                                </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <button class="btn btn-primary mt-2" id="editarInfo">Actualizar información</button>
                            <button class="btn btn-danger mt-2" id="eliminarInfo">Eliminar información</button>
                            <button class="btn btn-warning mt-2" id="agregarObservacion">Agregar Observación</button>  
                        </div>
                    </div>      
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="observaciones"></div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>
<input type="hidden" id="idEmpleado">
<?php include('includes/crearobservaciones.php')  ?>
</body>
</html>