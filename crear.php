<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/crear-editar.js"></script>
    <script src="js/sweetalert2.min.js"></script>
</head>
<body>
<?php include("includes/menu.php") ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-body">
                    <h4>Empleada</h4>
                    <hr>
                    <button data-type="empleada" class="btn btn-primary crear">Crear</button> <button class="btn btn-warning editar" data-type="Empleada">Editar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-body">
                    <h4>Habitación</h4>
                    <hr>
                    <button data-type="habitación" class="btn btn-primary crear">Crear</button> <button class="btn btn-warning editar" data-type="habitación">Editar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="card mt-4">
                    <div class="card-body">
                    <h4>Empresa</h4>
                    <hr>
                    <button class="btn btn-warning" id="editarEmpresa" data-type="habitación">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/crear.php'); include("includes/editar.php"); include('includes/empresa.php'); ?>
</body>
</html>