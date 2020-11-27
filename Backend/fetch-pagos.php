<?php
include('connection.php');
if((isset($_POST["empleada"]) && !empty($_POST["empleada"])) && (isset($_POST["inicio"]) && !empty($_POST["inicio"])) && (isset($_POST["final"]) && !empty($_POST["final"]))){
$inicio = $_POST["inicio"];
$final = $_POST["final"];
$empleada = utf8_decode($_POST["empleada"]);

if($empleada == "Todos"){
$stmt = $conn->prepare("SELECT * FROM Pagos WHERE Fecha_de_Pago BETWEEN :fechaInicio AND :fechaFinal;");
$count = $conn->prepare("SELECT COUNT(*) FROM Pagos WHERE Fecha_de_Pago BETWEEN :fechaInicio AND :fechaFinal;");
$result = $stmt->execute([
    "fechaInicio" => $inicio,
    "fechaFinal" => $final
]);
$entradas = $count->execute([
    "fechaInicio" => $inicio,
    "fechaFinal" => $final
]);
if($result && $count->fetchColumn() >=1){
    $json = [];
    while ($entrada = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $json[] = [
            "idPago" => $entrada["ID_Pago"],
            "idEmpleada" => $entrada["ID_Empleado"],
            "nombreEmpleada" => utf8_encode($entrada["Nombre_Empleado"]),
            "pago" => $entrada["Pago"],
            "descuento" => $entrada["Descuento"],
            "total" => $entrada["Total"],
            "fecha" => $entrada["Fecha_de_Pago"],
            "usuario" =>utf8_encode($entrada["Usuario"])
        ];
    }
    echo json_encode($json);
}else{
    echo "No se ha encontrado ningún registro de pago";
}
}else{
// En caso de ser una empleada en específico
$stmt = $conn->prepare("SELECT * FROM Pagos WHERE Nombre_Empleado =:nombre AND Fecha_de_Pago BETWEEN :fechaInicio AND :fechaFinal;");
$count = $conn->prepare("SELECT COUNT(*) FROM Pagos WHERE Nombre_Empleado =:nombre AND Fecha_de_Pago BETWEEN :fechaInicio AND :fechaFinal;");
$result = $stmt->execute([
    "fechaInicio" => $inicio,
    "fechaFinal" => $final,
    "nombre" => $empleada
]);
$entradas = $count->execute([
    "fechaInicio" => $inicio,
    "fechaFinal" => $final,
    "nombre" => $empleada
]);
if($result && $count->fetchColumn() >=1){
    $json = [];
    while ($entrada = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $json[] = [
            "idPago" => $entrada["ID_Pago"],
            "idEmpleada" => $entrada["ID_Empleado"],
            "nombreEmpleada" => utf8_decode($entrada["Nombre_Empleado"]),
            "pago" => $entrada["Pago"],
            "descuento" => $entrada["Descuento"],
            "total" => $entrada["Total"],
            "fecha" => $entrada["Fecha_de_Pago"],
            "usuario" =>utf8_decode($entrada["Usuario"])
        ];
    }
    echo json_encode($json);
}else{
    echo "No se ha encontrado ningún registro de pago";
}
}


}else{
    echo "Debe seleccionar fechas de inicio y final";
}
?>