<?php
include("Backend/fpdf/fpdf.php");
include("Backend/connection.php");
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
    $stmt = $conn->prepare("SELECT Nombre_Empresa from Empresa WHERE ID = (SELECT MAX(ID) from Empresa)");
    $stmt->execute();
    $empresa = $stmt->fetchColumn();
$pdf = new FPDF("p", 'mm', [72.1,120]);
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->setMargins(0,5,0);
$pdf->SetAutoPageBreak(false);
$pdf->Cell(60,1,"",0,1,"L",0);
//Row
$pdf->Cell(17,5,"",0,0,"C",0);
$pdf->Cell(40,5,"Comprobante",1,1,"C",0);
$pdf->Cell(5,5,"",0,1,"C",0);
$pdf->SetFont('Arial', '', 8);
//Row
$pdf->Cell(17,5,"",0,0,"C",0);
$pdf->Cell(40,5,$empresa,1,1,"C",0);
$pdf->Cell(5,5,"",0,1,"C",0);
$pdf->SetFont('Arial', '', 8);
//Row
$pdf->cell(30,5, "Fecha emitido:",0,0, "C", 0);
$pdf->cell(30,5, date("d-m-Y h:i:sa"),0,1, "C", 0);

//Row
$pdf->cell(15,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,10, "Empleada:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(30,10, utf8_decode($nombre),0,0, "L", 0);
$pdf->cell(0,10, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(14,5, "Servicio:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(31,5, utf8_decode($servicio),0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);

//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(12,5, "Cajero:",0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(33,5, utf8_decode($cajero),0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(14,5, utf8_decode("Duración:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(31,5,$duracion,0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(18,5, utf8_decode("Habitación:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(27,5,$habitacion,0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,5, utf8_decode("Inicio:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$inicio,0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,5, utf8_decode("Final:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(35,5,$final,0,0, "L", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row

$pdf->cell(72,10, "********************************************************",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(10,5, utf8_decode("Monto:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(20,5, "",0,0, "C", 0);
$pdf->cell(15,5,$empleada,0,0, "R", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,5, utf8_decode("Descuento:"),0,0, "L", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->cell(15,5,$descuentos,0,0, "R", 0);
$pdf->cell(0,5, "",0,1, "C", 0);
//Row

$pdf->cell(72,5, "---------------------------------------------------------------",0,1, "C", 0);
//Row
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(15,5, utf8_decode("Total:"),0,0, "L", 0);
$pdf->cell(15,5, "",0,0, "C", 0);
$pdf->cell(15,5,$total,0,0, "R", 0);
$pdf->cell(0,5, "",0,1, "C", 0);

$pdf->cell(72,5, "",0,1, "C", 0);
$pdf->cell(72,5, "",0,1, "C", 0);



$pdf->output();
}
?>