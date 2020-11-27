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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link rel="stylesheet" href="css/Chart.min.css">
    <link rel="stylesheet" href="css/graficos.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <script src="js/daterangepicker.js"></script>
    <script src="js/exportar/html2canvas.min.js"></script>
    <script src="js/exportar/jspdf.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/charts.js"></script>
</head>
<body>
<input type="hidden" value="<?php usernameValue(); ?>" id="username">
<?php include('includes/menu.php'); ?>
<div class="container">
<div class="row">
<div class="col-md-4 my-3">
<div id="dateRange" class="" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>
</div>
</div>
<div class="col-md-3 my-2">
<select class="form-control" id="plazo">
<option value="dias">Días</option>
<option value="mes">Meses</option>
<option value="year">Años</option>
</select>
</div>
<div class="col-md-2 my-2">
    <button id="graficar" class="btn btn-primary">Mostrar</button>
</div>
<div class="col-md-2 my-2">
    <button id="PDF" class="btn btn-warning">PDF</button>
</div>
</div>
<div id="datos"></div>
<div id="chart" class="m-2">
<canvas id="myChart" ></canvas>
</div>
</div>
</body>
</html>
