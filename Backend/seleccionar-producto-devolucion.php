<?php
include('connection.php');
if((isset($_POST["id"]) && !empty($_POST["id"])) && (isset($_POST["numFactura"]) && !empty($_POST["numFactura"]))){
$id = $_POST["id"];
$numFactura = $_POST["numFactura"];
try{
$stmt = $conn->prepare("SELECT * from Kardex where ID_Producto =:id AND Numero_de_Factura =:numFactura AND Tipo_de_Operacion = 'venta'");
$count = $conn->prepare("SELECT COUNT(*) from Kardex where  ID_Producto =:id AND Numero_de_Factura =:numFactura");

$devoluciones = $conn->prepare("SELECT SUM(Cantidad_Salidas) from Kardex where  ID_Producto =:id AND Numero_de_Factura =:numFactura AND Tipo_de_Operacion <> 'Venta'");

$result2 = $devoluciones->execute([":id" => $id, "numFactura" => $numFactura]);

$count->execute([":id" => $id, "numFactura" => $numFactura]);
$result = $stmt->execute([":id" => $id, "numFactura" => $numFactura]);

if($result && $count->fetchColumn() >=1){
    $json = [];
    $selectCliente = $conn->prepare("SELECT Cliente, Total, Cantidad_Vendida FROM Ventas WHERE Numero_Factura =:numFactura AND ID_Producto =:idProducto;");
    $selectCliente->execute(["numFactura" => $numFactura, "idProducto" => $id]);
    $cliente = $selectCliente->fetch(PDO::FETCH_ASSOC);
    $nombreCliente = utf8_encode($cliente["Cliente"]);
    $Total = utf8_encode($cliente["Total"]);
    $Cantidad_Vendida = utf8_encode($cliente["Cantidad_Vendida"]);
    $unidadesDevueltas = $devoluciones->fetchColumn();
    if($Cantidad_Vendida >0){
    while($producto = $stmt->fetch(PDO::FETCH_ASSOC)){
        $cantidad = $producto["Cantidad_Salidas"] + $unidadesDevueltas;
        $json[] = [
            "idProducto" => $producto["ID_Producto"],
            "nombreProducto" => utf8_encode($producto["Nombre_Producto"]),
            "precioProducto" => ( $Total/$Cantidad_Vendida),
            "cantidadTotal" => $cantidad,
            "Cliente" => $nombreCliente
        ];
    }
    echo json_encode($json);
}else{
    echo "No se pueden hacer más devoluciones de esta venta";
}
}else{
    echo "No se ha encontrado esta venta";
}
}catch(Exception $e){
throw $e;
}

}else{
    echo "No se ha seleccionado ningún producto. Vuelve a intentarlo";
}
?>