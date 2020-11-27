<?php
include('connection.php');
if((isset($_POST["idEmpleado"]) && !empty($_POST["idEmpleado"])) && (isset($_POST["dui"]) && !empty($_POST["dui"])) && (isset($_POST["observacion"]) && !empty($_POST["observacion"]))){

    $dui = $_POST["dui"];
    $idEmpleado = $_POST["idEmpleado"];
    $observacion = utf8_decode($_POST["observacion"]);
    $usuario = utf8_decode($_POST["username"]);
    $stmt = $conn->prepare("INSERT INTO Observaciones (ID_Empleado, DUI, Observacion, Fecha, Usuario) VALUES (:id, :dui, :observacion, NOW(), :usuario);");
    try{
    $result = $stmt->execute([
        "id" => $idEmpleado,
        "dui" => $dui,
        "observacion" => $observacion,
        "usuario" => $usuario
    ]);
    if($result){
        $json = ["msg" => "La observación se ha añadido correctamente"];
        echo json_encode($json);
    }
    }catch(PDOException $e){
        echo "Hubo un error al ingresar los datos ". $e->getMessage();
    }
}else{
    echo "Debe ingresar todos los datos";
}
?>