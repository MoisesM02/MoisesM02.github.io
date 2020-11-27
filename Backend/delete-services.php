<?php
include("connection.php");
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    $conn->beginTransaction();
    $stmt = $conn->prepare("DELETE from Servicios WHERE ID =:id ");
    try{
        $result = $stmt->execute(["id" => $id]);
    if($result){
        echo "Servicio eliminado correctamente";
    }else{
        echo "Ocurrió un problema al eliminar el producto";
        $conn->rollback();
    }
    }catch(PDOException $e){
        $conn->rollback();
        throw $e;
    }
    
}

?>