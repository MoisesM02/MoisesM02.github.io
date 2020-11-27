<?php
include('connection.php');
if(isset($_POST["fechaInicio"]) && isset($_POST["fechaFinal"])){
    $inicio = $_POST["fechaInicio"];
    $fin = $_POST["fechaFinal"];

    $stmt = $conn->prepare("SELECT * FROM Kardex WHERE Fecha Between :inicio AND :final;");
    $count = $conn->prepare("SELECT Count(*) FROM Kardex WHERE Fecha Between :inicio AND :final;");
    
    $result = $stmt->execute(["inicio" =>$inicio, "final" => $fin]);
    $cuenta = $count->execute(["inicio" => $inicio, "final" => $fin]);
    if($result && $count->fetchColumn() >=1){
        $json = [];
        while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
            $json [] = [
                "id" => $entrada["ID"],
                "idProducto" => $entrada["ID_Producto"],
                "proveedor" => utf8_encode($entrada["Proveedor"]),
                "numFactura" => $entrada["Numero_de_Factura"],
                "nombreProducto" => utf8_encode($entrada["Nombre_Producto"]),
                "fecha" => $entrada["Fecha"],
                "descripcion" => utf8_encode($entrada["Descripcion"]),
                "valorUnitario" => $entrada["Valor_Unitario"],
                "cantidadEntradas" => $entrada["Cantidad_Entradas"],
                "valorEntradas" => $entrada["Valor_Entradas"],
                "cantidadSalidas" => $entrada["Cantidad_Salidas"],
                "valorSalidas" => $entrada["Valor_Salidas"],
                "cantidadTotal" => $entrada["Cantidad_Total"],
                "valorTotal" => $entrada["Valor_Total"],
                "usuario" => utf8_encode($entrada["Usuario"]),
                "tipoOperacion" => utf8_encode($entrada["Tipo_de_Operacion"])
            ];
        }
        echo json_encode($json);
    }else{
        echo "No se han encontrado registros en este periodo";
    }
}else{
    echo "Debe seleccionar un rango de fecha";
}
?>