<?php
include('connection.php');
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    $stmt = $conn->prepare("DELETE FROM InformacionPersonal WHERE ID_Empleado =:id");
    
    try{
        $result = $stmt->execute(["id" => $id]);
        if($result){
            $json = ["msg" => "Se ha eliminado correctamente"];
            echo json_encode($json);
        }else{
                echo "Hubo un error al eliminar la información";
            }
    }catch(PDOException $e){
        echo "Hubo un problema: ". $e->getMessage();
    }
}
?>