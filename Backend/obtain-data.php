<?php
include('connection.php');
if((isset($_POST["fechaInicio"]) && !empty($_POST["fechaInicio"])) && (isset($_POST["fechaFinal"]) && !empty($_POST["fechaFinal"]))){
if(isset($_POST["duracion"]) && $_POST["duracion"] == "dias"){
$fechaInicio = $_POST["fechaInicio"];
$fechaFinal = $_POST["fechaFinal"];

$stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) AS gananciaCasa, SUM(Ganancia_Empleada) AS gananciaEmpleada, Creado_En FROM LibroServicios WHERE Creado_En BETWEEN :fechaInicio AND :fechaFinal GROUP BY Creado_En;");

$result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
if($result){
    if($stmt->rowCount() >=1){
    $json = [];
    
    
    while($entrada = $stmt->fetch(PDO::FETCH_ASSOC)){
        $json[] =[
            "fecha" => $entrada["Creado_En"],
            "gananciaCasa" => $entrada["gananciaCasa"],
            "gananciaEmpleada" => $entrada["gananciaEmpleada"]
        ];
       }
     
    
    echo json_encode($json);
    }else{
        echo "No se ha encontrado ningún registro entre las fechas seleccionadas";
    }
}else{
    echo "Algo salió mal." . $conn->rollback();
}
}elseif(isset($_POST["duracion"]) && $_POST["duracion"] == "mes"){

    $fechaInicio = $_POST["fechaInicio"];
    $fechaFinal = $_POST["fechaFinal"];
    $stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) as gananciaCasa, SUM(Ganancia_Empleada) AS gananciaEmpleada, DATE_FORMAT(Creado_En, '%Y-%m') as Fecha FROM LibroServicios WHERE Creado_En BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Creado_En, '%Y %m');");
    $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
    if($result){
        if($stmt->rowCount() >=1){
            $jsonMeses = [];
            while($entradas = $stmt->fetch(PDO::FETCH_ASSOC)){
                $jsonMeses[] = ["fecha" => $entradas["Fecha"], "gananciaCasa" => $entradas["gananciaCasa"],
                "gananciaEmpleada" => $entradas["gananciaEmpleada"]];
            }
            echo json_encode($jsonMeses);
        }
    }
}elseif(isset($_POST["duracion"]) && $_POST["duracion"] == "year"){

    $fechaInicio = $_POST["fechaInicio"];
    $fechaFinal = $_POST["fechaFinal"];
    $stmt = $conn->prepare("SELECT SUM(Ganancia_Casa) as gananciaCasa, SUM(Ganancia_Empleada) AS gananciaEmpleada, DATE_FORMAT(Creado_En, '%Y') as Fecha FROM LibroServicios WHERE Creado_En BETWEEN :fechaInicio AND :fechaFinal GROUP BY DATE_FORMAT(Creado_En, '%Y');");
    $result = $stmt->execute(["fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal]);
    if($result){
        if($stmt->rowCount() >=1){
            $jsonYear = [];
            while($entradas = $stmt->fetch(PDO::FETCH_ASSOC)){
                $jsonYear[] = ["fecha" => $entradas["Fecha"], "gananciaCasa" => $entradas["gananciaCasa"],
                "gananciaEmpleada" => $entradas["gananciaEmpleada"]];
            }
            echo json_encode($jsonYear);
        }
    }
}
}else{
    echo "Debe seleccionar fechas de inicio y de término";
}


?>