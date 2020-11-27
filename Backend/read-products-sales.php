<?php
include('connection.php');
if(isset($_POST["empleada"]) && isset($_POST["fechaInicio"]) && isset($_POST["fechaFinal"])){

$empleada = filter_var($_POST["empleada"], FILTER_SANITIZE_STRING);
$fechainicial = $_POST["fechaInicio"];
$fechaDeFin = $_POST["fechaFinal"];
$conn->beginTransaction();

if($empleada == "todos"){
$stmt = $conn->prepare("SELECT * from Ventas WHERE Fecha_de_Venta BETWEEN :fechaInicio AND :fechaFinal AND Cantidad_Vendida <> 0 ORDER BY ID_Venta DESC");
$count = $conn->prepare("SELECT COUNT(*) from Ventas WHERE Fecha_de_Venta BETWEEN :fechaInicio AND :fechaFinal AND Cantidad_Vendida <> 0 ORDER BY ID_Venta DESC");
$result =$stmt->execute(["fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin]);
$count->execute(["fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin]);

if($result && $numRows = $count->fetchColumn() >= 1){
$json = [];
while($registro = $stmt->fetch(PDO::FETCH_ASSOC)){
    $idVenta = $registro["ID_Venta"];
    $idProducto = $registro["ID_Producto"];
    $nombreCliente = utf8_encode($registro["Cliente"]);
    $nombreProducto = utf8_encode($registro["Nombre_Producto"]);
    $usuario = utf8_encode($registro["Usuario"]);
    $precioTotal = $registro["Total"];
    $gananciaEmpleada = $registro['Ganancia_Empleado'];
    $gananciaCasa = $registro["Ganancia_Casa"];
    $fechaVenta = date('d-M-yy H:i:s',strtotime(utf8_encode($registro['Fecha_de_Venta'])));
    $numFactura= utf8_encode($registro["Numero_Factura"]);
    $formaPago = utf8_encode($registro["Forma_de_Pago"]);
    $cantidad = utf8_encode($registro["Cantidad_Vendida"]);
     
    $json[] = [
        "idVenta" => $idVenta,
        "idProducto" => $idProducto,
        "nombreCliente" => $nombreCliente,
        "nombreProducto" => $nombreProducto,
        "usuario" => $usuario,
        "precioTotal" => $precioTotal,
        "gananciaEmpleada" => $gananciaEmpleada,
        "gananciaCasa" => $gananciaCasa,
        "fechaVenta" => $fechaVenta,
        "numFactura" => $numFactura,
        "formaPago" => $formaPago,
        "cantidad" => $cantidad
    ];
    
}


$totales = ["Cliente" => 'todos'];
$data = [$json, $totales];
echo json_encode($data);


}else{
    echo "No se encontraron los datos buscados.";
}

}else{
$stmt = $conn->prepare("SELECT * from Ventas WHERE Cliente =:empleada AND (Fecha_de_Venta BETWEEN :fechaInicio AND :fechaFinal) AND Cantidad_Vendida <> 0 ORDER BY ID_Venta DESC");
$result =$stmt->execute(["empleada" => $empleada,
"fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin
]);
if($result && $numRows = $stmt->rowCount() >= 1){
$gananciaTotal = $conn->prepare("SELECT SUM(Total) as GananciaTotal, SUM(Ganancia_Casa) as gananciaCasa,  SUM(Ganancia_Empleado) as gananciaEmpleada, Cliente FROM Ventas WHERE Cliente =:empleada AND (Fecha_de_Venta BETWEEN :fechaInicio AND :fechaFinal) AND Cantidad_Vendida <> 0");
$gananciaTotal->execute(["empleada" => $empleada,
"fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin
]);
$totales = $gananciaTotal->fetch(PDO::FETCH_ASSOC);
$json = [];
while($registro = $stmt->fetch(PDO::FETCH_ASSOC)){
    $idVenta = $registro["ID_Venta"];
    $idProducto = $registro["ID_Producto"];
    $nombreCliente = utf8_encode($registro["Cliente"]);
    $nombreProducto = utf8_encode($registro["Nombre_Producto"]);
    $usuario = utf8_encode($registro["Usuario"]);
    $precioTotal = $registro["Total"];
    $gananciaEmpleada = $registro['Ganancia_Empleado'];
    $gananciaCasa = $registro["Ganancia_Casa"];
    $fechaVenta = date('d-M-yy H:i:s',strtotime(utf8_encode($registro['Fecha_de_Venta'])));
    $numFactura= utf8_encode($registro["Numero_Factura"]);
    $formaPago = utf8_encode($registro["Forma_de_Pago"]);
    $cantidad = utf8_encode($registro["Cantidad_Vendida"]);
     
    $json[] = [
        "idVenta" => $idVenta,
        "idProducto" => $idProducto,
        "nombreCliente" => $nombreCliente,
        "nombreProducto" => $nombreProducto,
        "usuario" => $usuario,
        "precioTotal" => $precioTotal,
        "gananciaEmpleada" => $gananciaEmpleada,
        "gananciaCasa" => $gananciaCasa,
        "fechaVenta" => $fechaVenta,
        "numFactura" => $numFactura,
        "formaPago" => $formaPago,
        "cantidad" => $cantidad
    ];
    
}


$data = [$json,  $totales];
echo json_encode($data);

}else{
    echo "No se encontraron los datos buscados.";
}
}
}else{
    echo "Debe seleccionar una fecha y una empleada";
}
?>