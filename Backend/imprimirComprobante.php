<?php
include("fpdf/fpdf.php");
session_start();
if(isset($_SESSION["comprobante"]) && !empty($_SESSION["comprobante"])){
    $datos = $_SESSION["comprobante"];
    $nombre = $datos["nombre"];
    $servicio = $datos["servicio"];
    $tipo = $datos["tipo"];
    $cajero = $datos["cajero"];
    $precio = $datos["precio"];
    $empleada = $datos["empleada"];
    $descuentos = $datos["descuentos"];
    $total = $datos["total"];
    $inicio = $datos["fechaInicio"];
    $final = $datos["fechaFinal"];
    $habitacion = $datos["habitacion"];
    $duracion = $datos["duracion"];

$pdf = new FPDF("p", 'mm', [80,120]);
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->setMargins(0,5,0);
$pdf->Cell(80,1,"",0,1,"L",0);
//Row
$pdf->Cell(20,5,"",0,0,"C",0);
$pdf->Cell(40,5,"Comprobante",1,1,"C",0);
$pdf->Cell(20,5,"",0,1,"C",0);
$pdf->SetFont('Arial', '', 8);
//Row
$pdf->cell(50,10, "Fecha emitido:",0,0, "C", 0);
$pdf->cell(10,10, date("d-m-Y h:i:sa"),0,0, "C", 0);
$pdf->cell(20,10, "",0,1, "C", 0);
//Row
$pdf->cell(5,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,10, "Empleada:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,10, $nombre,0,0, "L", 0);
$pdf->cell(25,10, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(14,5, "Servicio:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5, $servicio,0,0, "L", 0);
$pdf->cell(26,5, "",0,1, "C", 0);

//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(12,5, "Cajero:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5, $cajero,0,0, "L", 0);
$pdf->cell(28,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(14,5, utf8_decode("Duración:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$duracion,0,0, "L", 0);
$pdf->cell(26,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(18,5, utf8_decode("Habitación:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$habitacion,0,0, "L", 0);
$pdf->cell(22,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,5, utf8_decode("Inicio:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$inicio,0,0, "L", 0);
$pdf->cell(30,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,5, utf8_decode("Final:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$final,0,0, "L", 0);
$pdf->cell(30,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,10, utf8_decode("Monto:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(30,10, "",0,0, "C", 0);
$pdf->cell(30,10,$empleada,0,0, "R", 0);
$pdf->cell(5,10, "",0,1, "C", 0);
//Row
$pdf->cell(5,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,5, utf8_decode("Descuento:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(25,5, "",0,0, "C", 0);
$pdf->cell(30,5,$descuentos,0,0, "R", 0);
$pdf->cell(5,5, "",0,1, "C", 0);
//Row
$pdf->cell(5,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,10, utf8_decode("Total:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(25,10, "",0,0, "C", 0);
$pdf->cell(30,10,$total,0,0, "R", 0);
$pdf->cell(5,10, "",0,1, "C", 0);



$pdf->output();
}
?>