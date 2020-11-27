<?php
session_start();
include("Backend/connection.php");
include("Backend/fpdf/fpdf.php");
$productos = $_SESSION["venta"];
$numFactura = $_SESSION["factura"];
$pagado = $_SESSION["cantidadPagada"];
$cajero = $_SESSION["cajero"];
$stmt = $conn->prepare("SELECT Nombre_Empresa from Empresa WHERE ID = (SELECT MAX(ID) from Empresa)");
    $stmt->execute();
    $empresa = $stmt->fetchColumn();

$pdf = new FPDF("p", 'mm', [72,125]);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->setMargins(0,5,0);
$pdf->Cell(80,1,"",0,1,"L",0);

$pdf->cell(45,5, "",0,0, "C", 0);
$pdf->cell(10,5, date("d-m-Y h:i:sa"),0,0, "C", 0);
$pdf->cell(17,5, "",0,1, "C", 0);
//Row
$pdf->Cell(17,5,"",0,0,"C",0);
$pdf->Cell(40,5,"Comprobante",1,1,"C",0);
$pdf->Cell(5,5,"",0,1,"C",0);
$pdf->SetFont('Arial', '', 8);
//Row
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(72,5, utf8_encode($empresa),0,1, "C", 0);
//Row
$pdf->cell(5,10, "",0,0, "C", 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(20,10, "No. Factura",0,0, "C", 0);
$pdf->SetFont('Arial', '', 8);
$pdf->cell(20,10, utf8_encode($numFactura),0,0, "C", 0);
$pdf->cell(20,10, "",0,1, "C", 0);
//Row
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(18,5, "Nombre",0,0, "C", 0);
$pdf->cell(18,5, "Cantidad",0,0, "C", 0);
$pdf->cell(18,5, "Precio",0,0, "C", 0);
$pdf->cell(18,5, "Total",0,1, "C", 0);
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
    $pdf->cell(18,5, $nombre,0,0, "C", 0);
    $pdf->cell(18,5, $cantidad,0,0, "C", 0);
    $pdf->cell(18,5, "$".$precio,0,0, "C", 0);
    $pdf->cell(18,5, "$".$cantidad*$precio,0,1, "C", 0);
    $total += $cantidad*$precio;
}
$cambio = $pagado-$total;
$pdf->SetFont('Arial', 'B', 8);
$pdf->cell(18,5, "Total",0,0, "C", 0);
$pdf->setFont("Arial","", 8);
$pdf->cell(18,5, "",0,0, "C", 0);
$pdf->cell(18,5, "",0,0, "C", 0);
$pdf->cell(18,5, "$".$total,0,1, "C", 0);
//Row
$pdf->Cell(5,5,"",0,0,"L",0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(31,5,"Pagado",0,0,"L",0);
$pdf->Cell(18,5,"",0,0,"L",0);
$pdf->setFont("Arial","", 8);
$pdf->Cell(18,5,"$".$pagado,0,1,"C",0);
//Row
$pdf->Cell(5,10,"",0,0,"L",0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(31,5,"Cambio",0,0,"L",0);
$pdf->Cell(18,5,"",0,0,"L",0);
$pdf->setFont("Arial","", 8);
$pdf->Cell(18,5,"$".$cambio ,0,1,"C",0);
//Row
$pdf->Cell(5,5,"",0,0,"L",0);
$pdf->Cell(10,5,"Cajero:",0,0,"L",0);
$pdf->setFont("Arial","B", 8);
$pdf->Cell(20,5,$cajero,0,0,"L",0);
$pdf->Cell(35,5,'',0,1,"L",0);
//Mostrar documento en formato PDF
$pdf->Cell(72,10,'',0,1,"L",0);
$pdf->Cell(72,10,'',0,1,"L",0);
$pdf->output();
//  unset($_SESSION["venta"]);
?>