<?php
include('connection.php');
$json = [];
if(isset($_POST["id"]) && !empty($_POST["id"])){
$conn->beginTransaction();
$id = $_POST["id"];
$select = $conn->prepare("SELECT COUNT(*) FROM Pagos WHERE ID_Pago =:id;");
$check = $select->execute(["id" => $id]);
if($check && $select->fetchColumn()>=1){
    
    $stmt = $conn->prepare("DELETE FROM Pagos WHERE ID_Pago =:id AND Fecha_de_Pago >= NOW() - INTERVAL 5 MINUTE;");
    $result = $stmt->execute(["id" => $id]);
    $select2 = $conn->prepare("SELECT COUNT(ID_Pago) FROM Pagos WHERE ID_Pago =:id;");
    $check2 = $select2->execute(["id" => $id]);
    $cantidad = $select2->fetchColumn();
    if($result && $cantidad == 1 ){
        $json = ["msg" => "Ocurrió un error al eliminar el pago. Probablemente tenga más de 5 minutos de antigüedad", "icon" => "error"];
        
    }else{
        $json = ["msg" => "Se ha eliminado correctamente ", "icon" => "success"];
    }
}else{
    $json = ["msg" => "Este pago no se ha encontrado", "icon" => "error"];
}


}else{
    $json = ["msg" => "Debe seleccionar un pago", "icon" => "error"];
}
$conn->commit();
echo json_encode($json);
?>