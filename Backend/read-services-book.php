<?php
include("connection.php");

if(isset($_POST["empleada"]) && isset($_POST["fechaInicio"]) && isset($_POST["fechaFinal"])){


$empleada = filter_var($_POST["empleada"], FILTER_SANITIZE_STRING);
$fechainicial = $_POST["fechaInicio"];
$fechaDeFin = $_POST["fechaFinal"];


if($empleada == "Todos"){
$stmt = $conn->prepare("SELECT * from LibroServicios WHERE Fecha_Inicio BETWEEN :fechaInicio AND :fechaFinal;");
$result =$stmt->execute(["fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin]);

if($result && $numRows = $stmt->rowCount() >= 1){
$json = [];
while($registro = $stmt->fetch(PDO::FETCH_ASSOC)){
    $idServicio = $registro["ID"];
    $nombreEmpleada = utf8_encode($registro["Nombre_Empleada"]);
    $tipo = utf8_encode($registro["Tipo"]);
    $usuario = utf8_encode($registro["Usuario"]);
    $precioFinal = $registro["Precio_Total"];
    $gananciaEmpleada = $registro['Ganancia_Empleada'];
    $gananciaCasa = $registro["Ganancia_Casa"];
    $descuentosEmpleada = $registro['Descuentos_Empleada'];
    $totalEmpleada = $registro['Total_Empleada'];
    $fechaInicio = date('d-M-yy H:i:s',strtotime(utf8_encode($registro['Fecha_Inicio'])));
    $fechaFinal = date('d-M-yy H:i:s',strtotime(utf8_encode($registro['Fecha_Finalizacion'])));
    $habitacion = utf8_encode($registro["Habitacion"]);
    $servicioPrestado = utf8_encode($registro["Servicio_Prestado"]);
    $Tiempo = utf8_encode($registro["Duracion_Servicio"]);
  
    $json[] = [
        "id" => $idServicio,
        "nombre" => $nombreEmpleada,
        "tipo" => $tipo,
        "usuario" => $usuario,
        "precioFinal" => $precioFinal,
        "gananciaEmpleada" => $gananciaEmpleada,
        "gananciaCasa" => $gananciaCasa,
        "descuentosEmpleada" => $descuentosEmpleada,
        "totalEmpleada" => $totalEmpleada,
        "fechaInicio" => $fechaInicio,
        "fechaFinal" => $fechaFinal,
        "habitacion" => $habitacion,
        "servicioPrestado" => $servicioPrestado,
        "tiempo" => $Tiempo
    ];
    
}


$totalPagesArray = [
    "numPaginas" => 0,
    "nombreEmpleada" => "Todos"
];

$data = [$json, $totalPagesArray];
echo json_encode($data);


}else{
    echo "No se encontraron los datos buscados.";
}
}else{
$stmt = $conn->prepare("SELECT * from LibroServicios WHERE Nombre_Empleada =:empleada AND (Fecha_Inicio BETWEEN :fechaInicio AND :fechaFinal OR Fecha_Finalizacion BETWEEN :fechaInicio AND :fechaFinal);");
$result =$stmt->execute(["empleada" => $empleada,
"fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin
]);
if($result && $numRows = $stmt->rowCount() >= 1){
$gananciaTotal = $conn->prepare("SELECT SUM(Total_Empleada) as GananciaTotalEmpleada, SUM(Ganancia_Casa) as gananciaCasa, SUM(Descuentos_Empleada) as descuentosEmpleada, SUM(Ganancia_Empleada) as gananciaEmpleada FROM LibroServicios WHERE Nombre_Empleada =:empleada AND (Fecha_Inicio BETWEEN :fechaInicio AND :fechaFinal OR Fecha_Finalizacion BETWEEN :fechaInicio AND :fechaFinal)");
$gananciaTotal->execute(["empleada" => $empleada,
"fechaInicio" => $fechainicial,
"fechaFinal" =>$fechaDeFin
]);
$totales = $gananciaTotal->fetch(PDO::FETCH_ASSOC);
$json = [];
while($registro = $stmt->fetch(PDO::FETCH_ASSOC)){
    $idServicio = $registro["ID"];
    $nombreEmpleada = utf8_encode($registro["Nombre_Empleada"]);
    $tipo = utf8_encode($registro["Tipo"]);
    $usuario = utf8_encode($registro["Usuario"]);
    $precioFinal = $registro["Precio_Total"];
    $gananciaEmpleada = $registro['Ganancia_Empleada'];
    $gananciaCasa = $registro["Ganancia_Casa"];
    $descuentosEmpleada = $registro['Descuentos_Empleada'];
    $totalEmpleada = $registro['Total_Empleada'];
    $fechaInicio = $registro['Fecha_Inicio'];
    $fechaFinal = $registro['Fecha_Finalizacion'];
    $habitacion = $registro["Habitacion"];
    $servicioPrestado = utf8_encode($registro["Servicio_Prestado"]);
    $Tiempo = utf8_encode($registro["Duracion_Servicio"]);
    $json[] = [
        "id" => $idServicio,
        "nombre" => $nombreEmpleada,
        "tipo" => $tipo,
        "usuario" => $usuario,
        "precioFinal" => $precioFinal,
        "gananciaEmpleada" => $gananciaEmpleada,
        "gananciaCasa" => $gananciaCasa,
        "descuentosEmpleada" => $descuentosEmpleada,
        "totalEmpleada" => $totalEmpleada,
        "fechaInicio" => $fechaInicio,
        "fechaFinal" => $fechaFinal,
        "tipo" => $tipo,
        "habitacion" => $habitacion,
        "servicioPrestado" =>$servicioPrestado,
        "tiempo" => $Tiempo
    ];
    
}



$totalPagesArray = [
    "numPaginas" => 0,
    "empleada" => $nombreEmpleada
];
$data = [$json, $totalPagesArray, $totales];
echo json_encode($data);

}else{
    echo "No se encontraron los datos buscados";
}
}
}else{
    echo "Debe seleccionar una fecha y una empleada";
}
?>