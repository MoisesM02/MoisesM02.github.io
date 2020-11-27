<?php
session_start();
include("fpdf/fpdf.php");
$productos = $_SESSION["venta"];
$numFactura = $_SESSION["factura"];
$pagado = $_SESSION["cantidadPagada"];
$cajero = $_SESSION["cajero"];
$pdf = new FPDF("p", 'mm', [80,125]);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->setMargins(0,5,0);
$pdf->Cell(80,1,"",0,1,"L",0);

$pdf->cell(50,5, "",0,0, "C", 0);
$pdf->cell(10,5, date("d-m-Y h:i:sa"),0,0, "C", 0);
$pdf->cell(20,5, "",0,1, "C", 0);
$pdf->cell(5,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(20,10, "No. Factura",0,0, "C", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(20,10, utf8_encode($numFactura),0,0, "C", 0);
$pdf->cell(35,10, "",0,1, "C", 0);

$pdf->cell(20,10, "Nombre",0,0, "C", 0);
$pdf->cell(20,10, "Cantidad",0,0, "C", 0);
$pdf->cell(20,10, "Precio",0,0, "C", 0);
$pdf->cell(20,10, "Total",0,1, "C", 0);
$total =0;
$pdf->setFont("Arial","", 8);
foreach($productos as $producto){
    $pago = $producto[0][6];
    $username = utf8_decode($producto[0][3]);
    $nombre = utf8_decode($producto[0][0]);
    $cliente = utf8_decode($producto[0][4]);
    $numeroFactura = utf8_decode($producto[0][5]);
    (float) $precio = $producto[0][1];
    (int) $cantidad = $producto[1][1];
    $pdf->cell(20,5, $nombre,0,0, "C", 0);
    $pdf->cell(20,5, $cantidad,0,0, "C", 0);
    $pdf->cell(20,5, "$".$precio,0,0, "C", 0);
    $pdf->cell(20,5, "$".$cantidad*$precio,0,1, "C", 0);
    $total += $cantidad*$precio;
}
$cambio = $pagado-$total;
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(20,10, "Total",0,0, "C", 0);
$pdf->setFont("Arial","", 8);
$pdf->cell(20,10, "",0,0, "C", 0);
$pdf->cell(20,10, "",0,0, "C", 0);
$pdf->cell(20,10, "$".$total,0,1, "C", 0);
$pdf->Cell(5,10,"",0,0,"L",0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35,10,"Pagado",0,0,"L",0);
$pdf->Cell(20,10,"",0,0,"L",0);
$pdf->setFont("Arial","", 8);
$pdf->Cell(20,10,"$".$pagado,0,1,"C",0);
$pdf->Cell(5,10,"",0,0,"L",0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35,5,"Cambio",0,0,"L",0);
$pdf->Cell(20,5,"",0,0,"L",0);
$pdf->setFont("Arial","", 8);
$pdf->Cell(20,5,"$".$cambio ,0,1,"C",0);
$pdf->Cell(5,10,"",0,0,"L",0);
$pdf->Cell(10,10,"Cajero:",0,0,"L",0);
$pdf->setFont("Arial","B", 8);
$pdf->Cell(20,10,$cajero,0,0,"L",0);
$pdf->Cell(35,10,'',0,0,"L",0);
//Mostrar documento en formato PDF
$pdf->output();
//  unset($_SESSION["venta"]);
?>