<?php
include('connection.php');
$stmt = $conn->prepare("SELECT ID_Producto, Nombre_de_Producto FROM Productos");
$result = $stmt->execute();
if($result && $numRows = $stmt->rowCount() >= 1){
    $json = [];
    while($servicio = $stmt->fetch(PDO::FETCH_ASSOC)){
        $json[] = [
            "id" => $servicio["ID_Producto"],
            "nombreProducto" => utf8_encode($servicio["Nombre_de_Producto"])
        ];
    }
    echo json_encode($json);
}

?>