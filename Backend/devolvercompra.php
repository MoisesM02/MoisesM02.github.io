<?php
include "connection.php";
if (isset($_POST) && count($_POST) >= 1) {
    $i = 0;
    $success = 0;
    try {
    $conn->beginTransaction();
        foreach ($_POST as $productos) {
            foreach ($productos as $producto) {
                if (!empty($producto[0][0]) && !empty($producto[0][3]) && !empty($producto[0][5])) {
                    $id = $producto[0][2];
                    $ids[] = $id;
                    $username = utf8_decode($producto[0][3]);
                    $nombre = utf8_decode($producto[0][0]);
                    $proveedor = utf8_decode($producto[0][4]);
                    $numeroFactura = utf8_decode($producto[0][5]);
                    (float) $precio = $producto[0][1];
                    (int) $cantidad = $producto[1][1];
                    if($cantidad<0){
                        echo "No puedes ingresar cantidades negativas";
                        $conn->rollback();
                        break;
                    }
                    
                    //Seleccionar último registro del producto (venta, devolucion, compra, etc.).
                    $total = $precio * $cantidad;
                    $selectStmt = $conn->prepare("SELECT * FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $selectStmt->execute(["idProducto" => $id]);
                    $count = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $count->execute(["idProducto" => $id]);

                    //Seleccionar compra que se devolverá
                    $selectCompraADevolver = $conn->prepare("SELECT * FROM Kardex WHERE Numero_de_Factura =:numFactura AND Proveedor =:proveedor AND Tipo_de_Operacion = 'Entrada' AND ID_Producto=:idProducto");
                    $ultimaventa = $selectCompraADevolver->execute(["numFactura" => $numeroFactura, "idProducto" => $id, "proveedor" => $proveedor]);
                    $count2 = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE Numero_de_Factura =:numFactura AND Descripcion = 'venta' AND ID_Producto=:idProducto");
                    $count2->execute(["numFactura" => $numeroFactura, "idProducto" => $id]);

                    $i = $count->fetchColumn();
                    $j = $count2->fetchColumn();
                    if ($i < 1 && $j<1){
                        echo "No se ha encontrado una entrada de esta compra.";
                        $conn->rollback();
                        break;
                    } else {
                        $lastEntry = $selectStmt->fetch(PDO::FETCH_ASSOC);
                        $compraADevolver = $selectCompraADevolver->fetch(PDO::FETCH_ASSOC);
                        $nuevoInventario = $lastEntry["Cantidad_Total"] - $cantidad;
                        if ($compraADevolver["Cantidad_Entradas"] - $cantidad < 0) {
                            echo "No puedes devolver más productos que los comprados". $compraADevolver["Cantidad_Entradas"]-$cantidad;
                            $conn->rollback();
                        }else{
                        $nuevoPrecioUnitario = ($lastEntry["Valor_Total"] - ($cantidad*($compraADevolver["Valor_Entradas"]/$compraADevolver["Cantidad_Entradas"])))/$nuevoInventario;
                        $nuevoValorInventario = $nuevoInventario * $nuevoPrecioUnitario;
                        $stmt2 = $conn->prepare("INSERT INTO Kardex (ID_Producto, Nombre_Producto, Fecha, Descripcion, Valor_Unitario, Cantidad_Entradas, Valor_Entradas, Cantidad_Salidas, Valor_Salidas, Cantidad_Total, Valor_Total, Usuario, Tipo_de_Operacion, Numero_de_Factura, Proveedor) VALUES (:id, :nombre, NOW(), :descripcion, :nuevoValorUnitario,  :cantidadEntrada, :valorEntrada, 0, 0, :nuevoTotalInventario, :nuevoValorInventario, :usuario, :tipoOperacion, :numeroFactura, :proveedor)");
                        $result = $stmt2->execute([
                            "id" => $id,
                            "descripcion" => utf8_decode("Devolución de compra"),
                            "nombre" => $nombre,
                            "nuevoValorUnitario" => $nuevoPrecioUnitario,
                            "cantidadEntrada" => $cantidad*-1,
                            "numeroFactura" =>$numeroFactura,
                            "valorEntrada" => -1*$cantidad*($compraADevolver["Valor_Entradas"]/$compraADevolver["Cantidad_Entradas"]),
                            "nuevoTotalInventario" => $nuevoInventario,
                            "nuevoValorInventario" => $nuevoValorInventario,
                            "tipoOperacion" => utf8_decode("Retorno"),
                            "usuario" => $username,
                            "proveedor" => $proveedor
                        ]);
                        $stmt = $conn->prepare("UPDATE Productos SET Cantidad_en_Stock = Cantidad_en_Stock - :cantidad WHERE ID_Producto =:id");
                        $result2 = $stmt->execute([
                            "cantidad" => $cantidad,
                            "id" => $id,
                        ]);
                        
                        $success++;
                        if (!$result ||  !isset($result) ) {
                            $conn->rollback();
                            echo " Algo salió mal";
                            break;
                        }
                        
                    }
                    }

                } else {
                    echo "Debe seleccionar un producto y llenar todos los datos.";
                    $conn->rollback();
                    break;

                }
            }
            if( $success == count($productos)){
            echo"Se ha actualizado correctamente";
            $conn->commit();
            }
        }

    } catch (Exception $e) {
        throw $e;
        echo "Hubo un problema al actualizar";
        $conn->rollback();
    }
} else {
    echo "Debe seleccionar un producto";
}
?>