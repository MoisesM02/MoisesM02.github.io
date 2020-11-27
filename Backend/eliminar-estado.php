<?php
include("connection.php");
if((isset($_POST["id"]) && !empty($_POST["id"])) && (isset($_POST["tipo"]) && !empty($_POST["tipo"])) && (isset($_POST["estado"]) && !empty($_POST["estado"]))){
    $id = $_POST["id"];
    $conn->beginTransaction();
    $estado = utf8_decode($_POST["estado"]);
    if($_POST["tipo"] == "Empleada"){
        $stmt = $conn->prepare("DELETE FROM Empleadas WHERE ID =:id");
        $result = $stmt->execute([ "id" => $id]);
        if($result){
            echo "Se ha aeliminado correctamente";
        }else{
            $conn->rollback();
            echo "Hubo un problema al eliminar los datos";
        }
    }else{
        #habitación
        $stmt = $conn->prepare("DELETE FROM Habitaciones WHERE ID =:id");
        $result = $stmt->execute([ "id" => $id]);
        if($result){
            echo "Se ha eliminado correctamente";
        }else{
            $conn->rollback();
            echo "Hubo un problema al eliminar los datos";
        }
    }
}else{
    echo "Debe enviar todos los datos";
}
$conn->commit();
?>