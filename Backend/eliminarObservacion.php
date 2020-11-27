<?php
include('connection.php');
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $conn->beginTransaction();
    $id = $_POST["id"];
    $stmt = $conn->prepare("DELETE from Observaciones WHERE ID_Observaciones =:id;");
    try{
        $result = $stmt->execute(["id" => $id]);
        $count = $conn->prepare("SELECT * FROM Observaciones WHERE ID_Observaciones =:id");
        $count->execute(["id" => $id]);
        if($result && $count->fetchColumn() == 0){
            $conn->commit();
            $json = ["msg" => ("Observación eliminada correctamente")];
            echo json_encode($json);
        }else{
            $conn->rollback();
            echo ("No se pudo eliminar la observación");
        }
    }catch(PDOException $e){
        echo "Ocurrió un error: ". $e->getMessage();
        $conn->rollback();
    }
}else{
    echo "Debe seleccionar una observación";
}
?>