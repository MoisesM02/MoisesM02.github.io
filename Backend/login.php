<?php
include("connection.php");
session_start();

if(!empty($_POST)){
    $username = utf8_decode(filter_var($_POST['usuario'], FILTER_SANITIZE_STRING));
    $password = utf8_decode(filter_var($_POST['contra'], FILTER_SANITIZE_STRING));

    
    $pwdEncrypted = hash("sha512", $password);
    $stmt = $conn->prepare("Select * from Usuarios where Nombre_de_Usuario =:user AND Password =:pwd");
    $stmt->execute(["user" => $username, "pwd" => $pwdEncrypted]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result['Nombre_de_Usuario'] == $username){
        $_SESSION["username"] = $username;
        $_SESSION["tipoUsuario"] = utf8_encode($result["Tipo_de_Usuario"]);
        
        if(utf8_encode($result["Tipo_de_Usuario"]) == "SU"){
            $url = "dashboard.php";
        }else{
            $url = "index.php";
        }
        $_SESSION["url"] = $url;
        $json = [
            "success" => TRUE,
            "message" => "Inicio de sesión correcto",
            "url" => $url
        ];
        echo json_encode($json);
    }else{
        echo "Nombre de usuario o contraseña incorrecta.";
    }
}

?>