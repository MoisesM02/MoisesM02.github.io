<?php
include('connection.php');
if(empty($_FILES["image"])){
   echo "Hace falta una imagen o no es posible enviar esa imagen";
}else{
    $image = $_FILES["image"];
//Validar el tamaño de la imagen
    $maxSize = 2*10e6;
    if($image["size"] > $maxSize){
        echo "El tamaño de la imagen no puede ser mayor a 2 MB";
    }else{
//Validar la imagen
        $imageData = getimagesize($image["tmp_name"]);
        if(!$imageData){
            echo "imagen no válida";
        }else{
//Validar formato de imagen
            $mimeType = $image['type'];
            $allowedMimeTypes = ["image/jpg", "image/png", "image/jpeg"];
            if(!in_array($mimeType, $allowedMimeTypes)){
                echo "Solo se admiten archivos .jpg y .png (su archivo es formato $mimeType)";
            }else{
//Preparar las variables para ejecutar sql
               if(isset($_POST["nombre"]) && !empty($_POST["nombre"])){
                try{
                $img = addslashes(file_get_contents($image["tmp_name"]));
                $name = utf8_decode($_POST["nombre"]);
                $stmt = $conn->prepare("INSERT INTO Empresa (Nombre_Empresa, img) VALUES(:nombre, '$img')");
                
                    $result = $stmt->execute([
                        "nombre" => $name
                    ]);
                    if($result){
                        $json = ["msg" => "Se ha actualizado correctamente"];
                        echo json_encode($json);
                    }else{
                        echo "No se pudo actualizar";
                    }
                }catch(Exception $e){
                    throw $e;
                }}else{
                    echo "No puede dejar campos en blanco";
                }
            }                    
        }
    }
}
?>
