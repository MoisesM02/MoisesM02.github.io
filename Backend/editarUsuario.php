<?php
include("connection.php");
if((isset($_POST["id"]) && !empty($_POST["id"]))  && (isset($_POST["pwd1"]) && !empty($_POST["pwd1"])) && (isset($_POST["pwd2"]) && !empty($_POST["pwd2"])) && (isset($_POST["username"]) && !empty($_POST["username"]))&& (isset($_POST["tipo"]) && !empty($_POST["tipo"]))){
$id = $_POST["id"];
$username = utf8_decode(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
$pwd1 = utf8_decode(filter_var($_POST["pwd1"], FILTER_SANITIZE_STRING));
$pwd2 = utf8_decode(filter_var($_POST["pwd2"], FILTER_SANITIZE_STRING));
$tipo = utf8_decode(filter_var($_POST["tipo"], FILTER_SANITIZE_STRING));
if(strlen($pwd1)>=8){
if($pwd1 == $pwd2){
    $pwdencrypted = hash('sha512', $pwd1);
    $conn->beginTransaction();
    $checkPassword = $conn->prepare("SELECT COUNT(*) FROM Usuarios WHERE Nombre_de_Usuario =:username AND `Password` =:pwd;");
    $checkPassword->execute([
        "username" => $username,
        "pwd" => $pwdencrypted
    ]);
    
    if($count = $checkPassword->fetchColumn() ==0){
        $stmt = $conn->prepare("UPDATE Usuarios SET Password =:pwd, Tipo_de_Usuario =:tipo WHERE ID =:id");
        $result = $stmt->execute([
            "pwd" => $pwdencrypted,
            "id" => $id,
            "tipo" => $tipo
        ]);
            if($result){
                $json = [
                    "msg" => "Se ha actualizado con éxito."
                ];
                echo json_encode($json);
                $conn->commit();
            }else{
                echo "Ha ocurrido un error.";
                $conn->rollback();
                $conn->commit();
            }
    }else{
        echo "No puede ingresar la misma contraseña";
    }
    
}else{
    echo "Las contraseñas no son iguales";
}
}else{
    echo "Las contraseñas deben tener un mínimo de 8 caracteres.";
}
}else{
    echo "Debes llenar todos los datos.";
}
?>