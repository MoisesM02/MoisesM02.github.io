<?php
include("connection.php");
$stmt = $conn->prepare("SELECT * FROM Usuarios");
$existen= $conn->prepare("SELECT COUNT(*) FROM Usuarios");
$result = $stmt->execute();
$existen->execute();
if($result && $existen->fetchColumn() >=1){
$json = [];
while($empleado = $stmt->fetch(PDO::FETCH_ASSOC)){
    $json[] = [
        "id" => $empleado["ID"],
        "username" => utf8_encode($empleado["Nombre_de_Usuario"]),
        "tipo" => utf8_encode($empleado["Tipo_de_Usuario"]),
        "creadoEn" => date("d/m/Y", strtotime($empleado["Creada_en"]))
    ];
}
    echo json_encode($json);
}else{
    echo "No se han encontrado usuarios.";
}
?>