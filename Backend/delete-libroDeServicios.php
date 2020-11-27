<?php 
include('connection.php');
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $conn->beginTransaction();
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("DELETE FROM LibroServicios WHERE ID =:id AND Fecha_Inicio >= NOW() - INTERVAL 5 MINUTE;");
    $result = $stmt->execute([
        "id" =>$id
    ]);
    $count = $conn->prepare("SELECT COUNT(*) FROM LibroServicios WHERE ID =:id;");
    $count->execute([
        "id" => $id
    ]);
    if($result && $count->fetchColumn() == 0){
        $json = ["msg" => "Se ha eliminado correctamente"];
        echo json_encode($json);
    }else{
        echo "No se pudo eliminar";
        $conn->rollback();
    }
}
?>