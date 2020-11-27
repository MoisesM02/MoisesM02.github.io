<?php
# Usado en recibosdeproductos.php
include "connection.php";
if (isset($_POST) && count($_POST) >= 1) {
    $i = 0;
    $success=0;
    $cantProduct =0;
    $conn->beginTransaction();
    try {
        foreach ($_POST as $productos) {
            
            foreach ($productos as $producto) {
                if(!empty($producto[0][0]) && !empty($producto[0][1]) && !empty($producto[0][2]) && !empty($producto[0][3]) && !empty($producto[0][4]) && !empty($producto[1][1] && !empty($producto[0][5]) )){
                $id = $producto[0][2];
                $ids[] = $id;
                $numFactura = ($producto[0][5]);
                $username = utf8_decode($producto[0][3]);
                $proveedor = utf8_decode($producto[0][4]);
                
                $nombre = utf8_decode($producto[0][0]);
                (float) $precio = $producto[0][1];
                (int) $cantidad = $producto[1][1];
                (float) $descuento = $producto[2][1];
                $precio = $precio - ($precio*($descuento/100));
                $total = $precio * $cantidad;
                $selectStmt = $conn->prepare("SELECT * FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                $selectStmt->execute(["idProducto" => $id]);
                $count = $conn->prepare("SELECT COUNT(*) FROM Kardex WHERE ID = (SELECT max(ID) FROM Kardex WHERE ID_Producto =:idProducto)");
                $count->execute(["idProducto" => $id]);
                $i = $count->fetchColumn();
                if ($i < 1) {
                    
                    $stmt2 = $conn->prepare("INSERT INTO Kardex (ID_Producto, Nombre_Producto, Fecha, Descripcion, Valor_Unitario, Cantidad_Entradas, Valor_Entradas, Cantidad_Salidas, Valor_Salidas, Cantidad_Total, Valor_Total, Usuario, Tipo_de_Operacion, Numero_de_Factura, Proveedor) VALUES (:id, :nombre, NOW(), 'Inventario inicial', :precioCompra, :cantidadEntrada, :valorEntrada, 0, 0, :cantidadEntrada, :valorEntrada, :usuario, 'Entrada', :numFactura, :proveedor)");
                    $valorEntrada = $precio *$cantidad;
                    $result = $stmt2->execute([
                        "id" => $id,
                        "nombre" => $nombre,
                        "precioCompra" => $precio,
                        "cantidadEntrada" => $cantidad,
                        "valorEntrada" => $valorEntrada,
                        "usuario" => "Empleada 1",
                        "numFactura" => "20190042",
                        "proveedor" => $proveedor
                    ]);
                    if(!$result){
                        echo "Algo salió mal";
                        $conn->rollback();
                    }
                }else{
                    $lastEntry = $selectStmt->fetch(PDO::FETCH_ASSOC);
                    $antiguoValorTotal = $lastEntry["Valor_Total"];
                    $valorDeCompra = $cantidad*$precio;
                    $nuevoInventario = $lastEntry["Cantidad_Total"] + $cantidad;

                    $nuevoPrecioUnitario = ($antiguoValorTotal+($cantidad*$precio))/$nuevoInventario;
                    $nuevoValorInventario = $nuevoInventario*$nuevoPrecioUnitario;
                    $stmt2 = $conn->prepare("INSERT INTO Kardex (ID_Producto, Nombre_Producto, Fecha, Descripcion, Valor_Unitario, Cantidad_Entradas, Valor_Entradas, Cantidad_Salidas, Valor_Salidas, Cantidad_Total, Valor_Total, Usuario, Tipo_de_Operacion, Numero_de_Factura, Proveedor) VALUES (:id, :nombre, NOW(), 'Compra', :nuevoValorUnitario, :cantidadEntrada, :valorEntrada, 0, 0, :nuevoTotalInventario, :nuevoValorInventario, :usuario, 'Entrada', :numFactura, :proveedor)");
                    $result = $stmt2->execute([
                        "id" => $id,
                        "nombre" => $nombre,
                        "nuevoValorUnitario" => $nuevoPrecioUnitario,
                        "cantidadEntrada" => $cantidad,
                        "valorEntrada" => $valorDeCompra,
                        "nuevoTotalInventario" => $nuevoInventario,
                        "nuevoValorInventario" => $nuevoValorInventario,
                        "usuario" => $username,
                        "numFactura" => $numFactura,
                        "proveedor" => $proveedor
                    ]);
                    if(!$result){
                        echo "Algo salió mal";
                        $conn->rollback();
                    }else{
                        $cantProduct++;
                        $success++;
                        $stmt = $conn->prepare("UPDATE Productos SET Cantidad_en_Stock = Cantidad_en_Stock +:cantidad WHERE ID_Producto =:id");
                $result = $stmt->execute([
                    "cantidad" => $cantidad,
                    "id" => $id,
                ]);
                
    
                    }
                }

                   
                
            }else{
            echo "Debe llenar todos los campos";
            print_r($productos);
            break;
            }
        }
        if($success == $cantProduct){
            echo "Se ha actualizado el inventario";
        } 
        }
       
    } catch (Exception $e) {
        echo "Hubo un problema al actualizar: ". $e->getMessage();
    }
}else{
    echo "Debe seleccionar un producto";
}
$conn->commit();
?>