<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link rel="stylesheet" href="css/informaciondepersonal.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert2.min.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <script src="js/daterangepicker.js"></script>
    <script src="js/cleave.min.js"></script>
    <script src="js/informacionPersonal.js"></script>

</head>
<body>
    <?php include("includes/menu.php");?>
    <div class="container my-3">
        <div class="row">
        <div class="col-md-3 "></div>
        <div class="col-md-6 ">
            <div class="card">
                <div class="card-header">
                <center><h3>Información personal</h3></center>
                </div>
                <div class="card-body">
                <form id="form" enctype="multipart/form-data">
                    <div class="img">
                        <img src="#" id="FotoPreview" alt="Foto de emplado">
                    </div>

                    <div class="input-group my-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="Foto" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="Foto">Seleccionar foto</label>
                        </div>
                    </div>


                    <hr>
                    <div class="form-group">
                        <label for="Nombre"> <strong>Nombres</strong></label>
                        <input type="text" placeholder="Nombre" id="Nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Apellido"> <strong>Apellidos</strong></label>
                        <input type="text" placeholder="Apellidos" id="Apellido" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for=""> <strong> Fecha de nacimiento</strong></label>
                        <div id="reportrange" class="my-3" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 40%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="DUI"> <strong>DUI</strong></label>
                        <input type="text" placeholder="DUI" id="DUI" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Edad"> <strong>Edad</strong></label>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" placeholder="Edad" id="Edad" class="form-control" readonly> 
                            </div>
                            <div class="col-md-2"><label for="Edad" class="pt-2">Años</label></div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="Direccion"> <strong>Dirección</strong></label>
                        <input type="text" placeholder="Dirección" id="Direccion" class="form-control">
                    </div>
                    <button id="enviar" class="btn btn-primary">Confirmar</button>
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        </div>
    </div>

</body>
</html>