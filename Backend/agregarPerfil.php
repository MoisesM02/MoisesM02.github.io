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
               if((isset($_POST["nombres"]) && !empty($_POST["nombres"])) && (isset($_POST["apellidos"]) && !empty($_POST["apellidos"])) && (isset($_POST["direccion"]) && !empty($_POST["direccion"])) && (isset($_POST["dui"]) && !empty($_POST["dui"])) && (isset($_POST["edad"]) && !empty($_POST["edad"])) && (isset($_POST["fechaNacimiento"]) && !empty($_POST["fechaNacimiento"]))){
                try{
                $img = addslashes(file_get_contents($image["tmp_name"]));
                $nombres = utf8_decode(filter_var($_POST["nombres"], FILTER_SANITIZE_STRING));
                $apellidos = utf8_decode(filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING));
                $direccion = utf8_decode(filter_var($_POST["direccion"], FILTER_SANITIZE_STRING));
                $dui = filter_var($_POST["dui"], FILTER_SANITIZE_STRING);
                $edad = filter_var($_POST["edad"], FILTER_SANITIZE_STRING);
                $fechaNacimiento = filter_var($_POST["fechaNacimiento"], FILTER_SANITIZE_STRING);
                $stmt = $conn->prepare("INSERT INTO InformacionPersonal (Nombres_Empleado, Apellidos_Empleado, DUI,Fecha_Nacimiento, Lugar_Residencia, Edad, Imagen, Estado) VALUES(:nombre, :apellido, :dui, :fechaNacimiento, :lugarResidencia, :edad, '$img', 'Contratado')");
                
                    $result = $stmt->execute([
                        "nombre" => $nombres,
                        "apellido" => $apellidos,
                        "dui" => $dui,
                        "fechaNacimiento" => $fechaNacimiento,
                        "lugarResidencia" => $direccion,
                        "edad" => $edad
                    ]);
                    if($result){
                        $json = ["msg" => "Se ha añadido correctamente"];
                        echo json_encode($json);
                    }else{
                        echo "No se pudo agregar este perfil";
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
