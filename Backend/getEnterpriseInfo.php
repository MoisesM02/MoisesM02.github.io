<?php
include('connection.php');
$stmt = $conn->prepare("SELECT * FROM Empresa WHERE ID = (SELECT MAX(ID) FROM Empresa);");
$result = $stmt->execute();

if($result){
    $datosEmpresa = $stmt->fetch(PDO::FETCH_ASSOC);
    $json = [
        "nombre" => utf8_encode($datosEmpresa["Nombre_Empresa"]),
        "logo" => base64_encode($datosEmpresa["img"])
    ];
    echo json_encode($json);
}else{
    $json = [
        "nombre" => "Nombre de empresa",
        "logo" => "Sin logo"
    ];
    echo json_encode($json);
}

?>