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
                    $cliente = utf8_decode($producto[0][4]);
                    $numeroFactura = utf8_decode($producto[0][5]);
                    (float) $precio = $producto[0][1];
                    (int) $cantidad = $producto[1][1];
                    if($cantidad<0){
                        echo "No puedes ingresar cantidades negativas";
                        $conn->rollback();
                        break;
                    }
                    
                    $precio = $precio ;
                    $total = $precio * $cantidad;
                    $selectStmt = $conn->prepare("SELECT * FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $selectStmt->execute(["idProducto" => $id]);
                    $count = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $count->execute(["idProducto" => $id]);
                    $selectVentaADevolver = $conn->prepare("SELECT * FROM Kardex WHERE Numero_de_Factura =:numFactura AND Descripcion = 'venta' AND ID_Producto=:idProducto");
                    $ultimaventa = $selectVentaADevolver->execute(["numFactura" => $numeroFactura, "idProducto" => $id]);
                    $count2 = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE Numero_de_Factura =:numFactura AND Descripcion = 'venta' AND ID_Producto=:idProducto");
                    $count2->execute(["numFactura" => $numeroFactura, "idProducto" => $id]);
                    $i = $count->fetchColumn();
                    $j = $count2->fetchColumn();
                    if ($i < 1 && $j<1){
                        echo "No se ha encontrado una entrada de esta venta.";
                        $conn->rollback();
                        break;
                    } else {
                        $lastEntry = $selectStmt->fetch(PDO::FETCH_ASSOC);
                        $ventaADevolver = $selectVentaADevolver->fetch(PDO::FETCH_ASSOC);
                        $nuevoInventario = $lastEntry["Cantidad_Total"] + $cantidad;
                        if ($ventaADevolver["Cantidad_Salidas"] - $cantidad < 0) {
                            echo "No puedes devolver más productos que los vendidos". $ventaADevolver["Cantidad_Salidas"]-$cantidad;
                            $conn->rollback();
                        }else{
                        $nuevoPrecioUnitario = ($lastEntry["Valor_Total"] + ($cantidad*$ventaADevolver["Valor_Unitario"]))/$nuevoInventario;
                        $nuevoValorInventario = $nuevoInventario * $nuevoPrecioUnitario;
                        $stmt2 = $conn->prepare("INSERT INTO Kardex (ID_Producto, Nombre_Producto, Fecha, Descripcion, Valor_Unitario, Cantidad_Entradas, Valor_Entradas, Cantidad_Salidas, Valor_Salidas, Cantidad_Total, Valor_Total, Usuario, Tipo_de_Operacion, Numero_de_Factura) VALUES (:id, :nombre, NOW(), :descripcion, :nuevoValorUnitario, 0, 0, :cantidadSalida, :valorSalida, :nuevoTotalInventario, :nuevoValorInventario, :usuario, :tipoOperacion, :numeroFactura )");
                        $result = $stmt2->execute([
                            "id" => $id,
                            "descripcion" => utf8_decode("Devolución de venta"),
                            "nombre" => $nombre,
                            "nuevoValorUnitario" => $nuevoPrecioUnitario,
                            "cantidadSalida" => $cantidad*-1,
                            "numeroFactura" =>$numeroFactura,
                            "valorSalida" => $ventaADevolver["Valor_Unitario"]*$cantidad*-1,
                            "nuevoTotalInventario" => $nuevoInventario,
                            "nuevoValorInventario" => $nuevoValorInventario,
                            "tipoOperacion" => utf8_decode("Devolución"),
                            "usuario" => $username,

                        ]);
                        $stmt = $conn->prepare("UPDATE Productos SET Cantidad_en_Stock = Cantidad_en_Stock + :cantidad WHERE ID_Producto =:id");
                        $result2 = $stmt->execute([
                            "cantidad" => $cantidad,
                            "id" => $id,
                        ]);
                        $porcentajes = $conn->prepare("SELECT Porcentaje_Empleada, Porcentaje_Casa FROM Productos WHERE ID_Producto =:idProducto");
                        $porcentajes->execute(["idProducto" => $id]);
                        $porcentajesArray = $porcentajes->fetch(PDO::FETCH_ASSOC);
                        $porcentajeEmpleada = $porcentajesArray["Porcentaje_Empleada"];
                        $porcentajeCasa = $porcentajesArray["Porcentaje_Casa"];
                        

                        $selectVenta = $conn->prepare("SELECT * FROM Ventas WHERE Numero_Factura =:numFactura AND ID_Producto =:idProducto;");
                        $selectVenta->execute(["numFactura" => $numeroFactura, "idProducto" =>$id]);
                        $venta = $selectVenta->fetch(PDO::FETCH_ASSOC);

                        $actualizarVenta = $conn->prepare('UPDATE Ventas SET Cantidad_Vendida = Cantidad_Vendida-:cantidad, Total =:nuevoTotal, Ganancia_Casa =:gananciaCasa, Ganancia_Empleado =:gananciaEmpleado WHERE Numero_Factura =:numeroFactura AND ID_Producto =:idProducto;');
                        $precioPorUnidad = $venta["Total"]/$venta["Cantidad_Vendida"];
                        $nuevoTotal = $precioPorUnidad*($venta["Cantidad_Vendida"]-$cantidad);
                        if(utf8_encode($venta["Cliente"]) != "general"){
                        $gananciaCasa = ($nuevoTotal*$porcentajeCasa)/100;
                        $gananciaEmpleada = ($nuevoTotal*$porcentajeEmpleada)/100;
                        }else{
                            $gananciaCasa = $nuevoTotal;
                            $gananciaEmpleada = 0;
                        }
                        $actualizacionVenta = false;
                        if($venta["Cantidad_Vendida"]-$cantidad<0){
                            echo "No puede devolver más productos que los vendidos.";
                            $conn->rollback();
                        }else{
                        $actualizacionVenta = $actualizarVenta->execute([
                            "numeroFactura" => $numeroFactura,
                            "idProducto" => $id,
                            "cantidad" => $cantidad,
                            "nuevoTotal" => $nuevoTotal,
                            "gananciaCasa" => $gananciaCasa,
                            "gananciaEmpleado" => $gananciaEmpleada
                        ]);
                        $success++;
                        }
                        if (!$result || !$actualizacionVenta || !isset($result) || !isset($actualizacionVenta)) {
                            echo " Algo salió mal";
                            
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