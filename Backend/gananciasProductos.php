<?php
include('connection.php');
if((isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"])) && (isset($_POST["fechaFinal"]) && !empty($_POST["fechaFinal"])) &&(isset($_POST["producto"]) && !empty($_POST["producto"]))){
    $producto = utf8_decode($_POST["producto"]);
    
    if(isset($_POST["duracion"]) && $_POST["duracion"] == "dias"){ //DÍAS --------------------------------------
    $fechaInicio = $_POST["fechaInicio"];
    $fechaFinal = $_POST["fechaFinal"];
    $json = [];
    if($producto == "todos"){
        //En caso de mostrar todos los productos

    $stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d')");
    
    $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
    if($result){
        if($stmt->rowCount() >=1){
        
        
        
        while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
            $json[] =[
                "fecha" => $entrada["Creado_En"],
                "gananciaCasa" => $entrada["gananciaCasa"],
                "gananciaEmpleada" => $entrada["gananciaEmpleado"]
            ];
           }

        
        echo json_encode($json);
        }else{
            echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
        }
    }else{
        echo "Algo salió mal." . $conn->rollback();
    }
}else{
    //En caso de buscar un solo producto
    $stmt = $conn->prepare("SELECT Nombre_Producto, SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND ID_Producto=:id AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d')");
    
    $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal, "id" => $producto]);
    if($result){
        if($stmt->rowCount() >=1){
        
        
        
        while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
            $json[] =[
                "fecha" => $entrada["Creado_En"],
                "gananciaCasa" => $entrada["gananciaCasa"],
                "gananciaEmpleada" => $entrada["gananciaEmpleado"]
            ];
           }

        
        echo json_encode($json);
        }else{
            echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
        }
    }else{
        echo "Algo salió mal." . $conn->rollback();
    }

    }
    }elseif(isset($_POST["duracion"]) && $_POST["duracion"] == "mes"){ //MESES---------------------------------
    
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFinal = $_POST["fechaFinal"];
        $json = [];
        if($producto == "todos"){
            //En caso de mostrar todos los productos
    
        $stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y-%m') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y-%m')");
        
        $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
        if($result){
            if($stmt->rowCount() >=1){
            
            
            
            while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
                $json[] =[
                    "fecha" => $entrada["Creado_En"],
                    "gananciaCasa" => $entrada["gananciaCasa"],
                    "gananciaEmpleada" => $entrada["gananciaEmpleado"]
                ];
               }
    
            
            echo json_encode($json);
            }else{
                echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
            }
        }else{
            echo "Algo salió mal." . $conn->rollback();
        }
    }else{
        //En caso de buscar un solo producto
        $stmt = $conn->prepare("SELECT Nombre_Producto, SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y-%m') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND ID_Producto=:id AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y-%m')");
        
        $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal, "id" => $producto]);
        if($result){
            if($stmt->rowCount() >=1){
            
            
            
            while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
                $json[] =[
                    "fecha" => $entrada["Creado_En"],
                    "gananciaCasa" => $entrada["gananciaCasa"],
                    "gananciaEmpleada" => $entrada["gananciaEmpleado"]
                ];
               }
    
            
            echo json_encode($json);
            }else{
                echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
            }
        }else{
            echo "Algo salió mal." . $conn->rollback();
        }
    
        }
    }elseif(isset($_POST["duracion"]) && $_POST["duracion"] == "year"){ //AÑOS---------------------------------
    
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFinal = $_POST["fechaFinal"];
        $json = [];
        if($producto == "todos"){
            //En caso de mostrar todos los productos
    
        $stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y-%m') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y')");
        
        $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
        if($result){
            if($stmt->rowCount() >=1){
            
            
            
            while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
                $json[] =[
                    "fecha" => $entrada["Creado_En"],
                    "gananciaCasa" => $entrada["gananciaCasa"],
                    "gananciaEmpleada" => $entrada["gananciaEmpleado"]
                ];
               }
    
            
            echo json_encode($json);
            }else{
                echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
            }
        }else{
            echo "Algo salió mal." . $conn->rollback();
        }
    }else{
        //En caso de buscar un solo producto
        $stmt = $conn->prepare("SELECT Nombre_Producto, SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleado) AS gananciaEmpleado, DATE_FORMAT(Fecha_de_Venta, '%Y') AS 'Creado_En' FROM Ventas WHERE Cantidad_Vendida <> 0 AND ID_Producto=:id AND DATE_FORMAT(Fecha_de_Venta, '%Y-%m-%d') BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Fecha_de_Venta, '%Y')");
        
        $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal, "id" => $producto]);
        if($result){
            if($stmt->rowCount() >=1){
            
            
            
            while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
                $json[] =[
                    "fecha" => $entrada["Creado_En"],
                    "gananciaCasa" => $entrada["gananciaCasa"],
                    "gananciaEmpleada" => $entrada["gananciaEmpleado"]
                ];
               }
    
            
            echo json_encode($json);
            }else{
                echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
            }
        }else{
            echo "Algo salió mal." . $conn->rollback();
        }
    
        }
    }else{
        echo "Debes seleccionar un rango de agrupación válido (días, meses, años)";
    }
    }else{
        echo "Debe seleccionar fechas de inicio y de término y un producto";
    }
    
?>