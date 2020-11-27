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
                    $pago = $producto[0][6];
                    (float)$cantidadPagada = $producto[0][7];
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
                    (float) $descuento = $producto[2][1];
                    $precio = $precio - ($precio * ($descuento / 100));
                    $total = $precio * $cantidad;
                    $selectStmt = $conn->prepare("SELECT * FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $selectStmt->execute(["idProducto" => $id]);
                    $count = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                    $count->execute(["idProducto" => $id]);
                    $i = $count->fetchColumn();
                    if ($i < 1) {
                        echo "No se ha encontrado una entrada de este artículo ($nombre).";
                        $conn->rollback();
                        break;
                    } else {
                        $lastEntry = $selectStmt->fetch(PDO::FETCH_ASSOC);
                        $antiguoValorTotal = $lastEntry["Valor_Total"];
                        $valorDeCompra = $cantidad * $precio;
                        $nuevoInventario = $lastEntry["Cantidad_Total"] - $cantidad;
                        if ($nuevoInventario < 0) {
                            echo "No puedes vender esta cantidad de $nombre ($cantidad) porque solo quedan " . $lastEntry["Cantidad_Total"] . ".";
                            $conn->rollback();
                        }else{
                        $nuevoPrecioUnitario = $lastEntry["Valor_Unitario"];
                        $nuevoValorInventario = $nuevoInventario * $nuevoPrecioUnitario;
                        $stmt2 = $conn->prepare("INSERT INTO Kardex (ID_Producto, Nombre_Producto, Fecha, Descripcion, Valor_Unitario, Cantidad_Entradas, Valor_Entradas, Cantidad_Salidas, Valor_Salidas, Cantidad_Total, Valor_Total, Usuario, Tipo_de_Operacion, Numero_de_Factura, Proveedor) VALUES (:id, :nombre, NOW(), :descripcion, :nuevoValorUnitario, 0, 0, :cantidadSalida, :valorSalida, :nuevoTotalInventario, :nuevoValorInventario, :usuario, 'Venta', :numeroFactura, '')");
                        $result = $stmt2->execute([
                            "id" => $id,
                            "descripcion" => "venta",
                            "nombre" => $nombre,
                            "nuevoValorUnitario" => $nuevoPrecioUnitario,
                            "cantidadSalida" => $cantidad,
                            "numeroFactura" => $numeroFactura,
                            "valorSalida" => $nuevoPrecioUnitario*$cantidad,
                            "nuevoTotalInventario" => $nuevoInventario,
                            "nuevoValorInventario" => $nuevoValorInventario,
                            "usuario" => $username
                        ]);
                        $stmt = $conn->prepare("UPDATE Productos SET Cantidad_en_Stock = Cantidad_en_Stock -:cantidad WHERE ID_Producto =:id");
                        $result2 = $stmt->execute([
                            "cantidad" => $cantidad,
                            "id" => $id,
                        ]);
                        $porcentajes = $conn->prepare("SELECT Porcentaje_Empleada, Porcentaje_Casa FROM Productos WHERE ID_Producto =:idProducto");
                        $porcentajes->execute(["idProducto" => $id]);
                        $porcentajesArray = $porcentajes->fetch(PDO::FETCH_ASSOC);
                        $porcentajeEmpleada = $porcentajesArray["Porcentaje_Empleada"];
                        $porcentajeCasa = $porcentajesArray["Porcentaje_Casa"];
                        if($cliente != "general"){
                        $gananciaCasa = number_format((($porcentajeCasa*$valorDeCompra)/100), 2, '.','');
                        $gananciaEmpleada = number_format((($porcentajeEmpleada*$valorDeCompra)/100),2,'.','');
                        }else{
                            $gananciaCasa = $valorDeCompra;
                            $gananciaEmpleada = 0;
                        }
                        $crearVenta = $conn->prepare('INSERT INTO Ventas (Numero_Factura, Fecha_de_Venta, ID_Producto, Nombre_Producto, Cantidad_Vendida, Total, Usuario, Cliente, Ganancia_Casa, Ganancia_Empleado, Forma_de_Pago) VALUES(:numeroFactura, NOW(), :idProducto, :nombreProducto, :cantidad, :precioTotal, :usuario, :cliente, :gananciaCasa, :gananciaEmpleada, :formaPago);');
                        
                        $creacionVenta = $crearVenta->execute([
                            "numeroFactura" => $numeroFactura,
                            "idProducto" => $id,
                            "nombreProducto" => $nombre,
                            "cantidad" => $cantidad,
                            "precioTotal" => $valorDeCompra,
                            "usuario" => $username,
                            "cliente" => $cliente,
                            "gananciaCasa" => $gananciaCasa,
                            "gananciaEmpleada" => $gananciaEmpleada,
                            "formaPago" => $pago
                        ]);

                        $success++;
                        
                        if (!$result) {
                            echo "Algo salió mal";
                            $conn->rollback();
                        }
                        if(!$creacionVenta){
                            echo "Algo salió mal";
                            $conn->rollback();
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
            $json =["msg" => "Se ha actualizado correctamente", "success" => "true"];
            echo json_encode($json);
            $conn->commit();
                session_start();
               
        }
        $_SESSION["venta"] = $productos;
        $_SESSION["factura"] = $productos[0][0][5];
        $_SESSION["cantidadPagada"] = $productos[0][0][7];
        $_SESSION["cajero"] = $productos[0][0][3];
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