<?php
if(isset($_POST["data"]) && !empty($_POST["data"])){
    $datos = $_POST["data"];
    session_start();
    $comprobante = [
        "nombre" => $datos[0],
        "servicio" => $datos[1],
        "tipo" => $datos[2],
        "cajero" => $datos[3],
        "precio" => $datos[4],
        "casa" => $datos[5],
        "empleada" => $datos[6],
        "descuentos" => $datos[7],
        "total" => $datos[8],
        "fechaInicio" => $datos[9],
        "fechaFinal" => $datos[10],
        "habitacion" => $datos[11],
        "duracion" => $datos[12]
    ];
    $_SESSION["comprobante"] = $comprobante;
}
?>