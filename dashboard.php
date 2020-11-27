<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/dashboard-menu.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/registro.js"></script>
</head>
<body>
<?php include('includes/menu.php');?>
  <div class="main">
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn close" onclick="closeNav()">&times;</a>
  <a href="informaciondepersonal.php">Añadir personal</a>
  <a href="observaciones.php">Añadir observaciones</a>
  <a href="#" data-toggle="modal" data-target="#staticBackdrop">Crear usuario</a>
  <a href="administrarUsuarios.php">Administrar usuarios</a>
  <a href="cerrarsesion.php">Cerrar sesión</a>
</div>   
<div class="row">
  <div class="col-md-1">
<a href="javascript:void(0)" onclick="openNav()">
<div class="container">
<div class="bar"></div>
<div class="bar"></div>
<div class="bar"></div>
</div>
</a>
</div>
</div>
<div class="row">
            <div class="col-md-1 mt-4"></div>
            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Productos</strong>
                    </div>
                    <div class="card-body">
                        <a href="productos.php" class="btn btn-warning">Administrar productos</a>
                        <br><br>
                        <a href="recibosdeproductos.php" class="btn btn-primary">Recibir productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Servicios </strong>
                    </div>
                    <div class="card-body">
                        <a href="servicios.php" class="btn btn-warning">Administrar servicios</a>
                        <br><br>
                        <a href="librodeservicios.php" class="btn btn-primary">Libro de servicios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Ganancias</strong>
                    </div>
                    <div class="card-body">
                        <a href="charts.php" class="btn btn-warning"> Ganancias de servicios</a>
                        <br> <br>
                        <a href="gananciasventas.php" class="btn btn-primary"> Ganancias de ventas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-1 mt-4"></div>
            <div class="col-md-1 mt-4"></div>
            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Ganancias</strong>
                    </div>
                    <div class="card-body">
                        <a href="gananciasporproductos.php" class="btn btn-warning"> Ganancias por productos</a>
                        <br> <br>
                        <a href="registrokardex.php" class="btn btn-primary"> Registro de productos</a>
                    </div>
                </div>
            </div>
        </div>


<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
  Launch static backdrop modal
</button> -->





<!-- Modal -->
<?php include('includes/register-modal.php'); ?>
<!-- Button trigger modal -->

</div>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
</body>
</html>