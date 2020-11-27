<?php
include('connection.php');
if((isset($_POST["id"]) && !empty($_POST["id"])) && (isset($_POST["numFactura"]) && !empty($_POST["numFactura"]))){
$conn->beginTransaction();
$id = $_POST["id"];
$numFactura = $_POST["numFactura"];
try{
$stmt = $conn->prepare("SELECT * from Kardex where ID_Producto =:id AND Numero_de_Factura =:numFactura AND Tipo_de_Operacion = 'Entrada'");
$count = $conn->prepare("SELECT COUNT(*) from Kardex where  ID_Producto =:id AND Numero_de_Factura =:numFactura");

$devoluciones = $conn->prepare("SELECT SUM(Cantidad_Entradas) as Devoluciones from Kardex where ID_Producto =:id AND Numero_de_Factura =:numFactura AND Descripcion =:descripcion ;");

$result2 = $devoluciones->execute(["id" => $id, "numFactura" => $numFactura, "descripcion" => utf8_decode('Devolución de compra')]);
$count->execute([":id" => $id, "numFactura" => $numFactura]);
$result = $stmt->execute([":id" => $id, "numFactura" => $numFactura]);

if($result && $count->fetchColumn() >=1){
    $json = [];
   
    $compraDevuelta = $devoluciones->fetch(PDO::FETCH_ASSOC);
    $unidadesDevueltas = $compraDevuelta["Devoluciones"];
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);
    $Cantidad_Restantes = $compra["Cantidad_Entradas"] + $unidadesDevueltas;
    
    if($Cantidad_Restantes>0){
   
        $cantidad = $compra["Cantidad_Entradas"] + $unidadesDevueltas;
        $json[] = [
            "idProducto" => $compra["ID_Producto"],
            "nombreProducto" => utf8_encode($compra["Nombre_Producto"]),
            "precioProducto" => ($compra["Valor_Entradas"]/$compra["Cantidad_Entradas"]),
            "cantidadTotal" => $cantidad,
            "proveedor" => $compra["Proveedor"]
        ];
    
    echo json_encode($json);
   
}else{
    echo "No se pueden hacer más devoluciones de esta venta";
}
}else{
    echo "No se ha encontrado esta venta";
}
}catch(PDOException $e){
echo $e->getMessage();
}

}else{
    echo "No se ha seleccionado ningún producto. Vuelve a intentarlo";
}
?>