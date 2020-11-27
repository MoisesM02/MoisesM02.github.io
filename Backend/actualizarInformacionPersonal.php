<?php
include('connection.php');
if((isset($_POST["nombres"]) && !empty($_POST["nombres"])) && (isset($_POST["id"]) && !empty($_POST["id"])) && (isset($_POST["apellidos"]) && !empty($_POST["apellidos"])) && (isset($_POST["residencia"]) && !empty($_POST["residencia"])) && (isset($_POST["edad"]) && !empty($_POST["edad"])) && (isset($_POST["dui"]) && !empty($_POST["dui"])) && (isset($_POST["estado"]) && !empty($_POST["estado"])) && (isset($_POST["fecha"]) && !empty($_POST["fecha"]))){
    $conn->beginTransaction();
    $idEmpleado = $_POST["id"];
    $nombres = utf8_decode($_POST["nombres"]);
    $apellidos = utf8_decode($_POST["apellidos"]);
    $edad = $_POST["edad"];
    $estado = utf8_decode($_POST["estado"]);
    $fecha = $_POST["fecha"];
    $dui = $_POST["dui"];
    $direccion = utf8_decode($_POST["residencia"]);

    $stmt = $conn->prepare("UPDATE InformacionPersonal SET Nombres_Empleado =:nombres, Apellidos_Empleado =:apellidos, DUI =:dui, Fecha_Nacimiento =:fecha, Lugar_Residencia =:residencia, Edad =:edad, Estado =:estado WHERE ID_Empleado =:id;");
try{
    $result = $stmt->execute([
        "nombres" => $nombres,
        "apellidos" => $apellidos,
        "dui" => $dui,
        "fecha" => $fecha,
        "residencia" => $direccion,
        "edad" => $edad,
        "estado" => $estado,
        "id" => $idEmpleado
    ]);
    if($result){
        $json = ["msg" =>"Se ha actualizado correctamente"];
        $conn->commit();
        echo json_encode($json);
    }else{
        echo "Hubo un error al actualizar"; 
        $conn->commit();
    }
}catch(PDOException $e){
    echo "Hubo un error: ". $e->getMessage();
}
}else{
    echo "No puede dejar campos vacíos";
    
}
?>