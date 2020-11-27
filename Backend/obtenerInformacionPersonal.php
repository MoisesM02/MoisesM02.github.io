<?php
include('connection.php');
if(isset($_POST["dui"]) && !empty($_POST["dui"])){
    $dui = filter_var($_POST["dui"], FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("SELECT * FROM InformacionPersonal WHERE DUI =:dui;");
    $count = $conn->prepare("SELECT COUNT(*) FROM InformacionPersonal WHERE DUI =:dui;");
    $result = $stmt->execute(["dui" => $dui]);
    $count->execute([
        "dui" => $dui 
    ]);
    
    if($result && $count->fetchColumn()>=1){
        $json = [];
        while ($personal = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $json[] = [
            "id" => $personal["ID_Empleado"],
            "nombres" => utf8_encode($personal["Nombres_Empleado"]),
            "apellidos" => utf8_encode($personal["Apellidos_Empleado"]),
            "dui" => $personal["DUI"],
            "fechaNacimiento" => date('m-d-Y',strtotime(utf8_encode($personal["Fecha_Nacimiento"]))),
            "residencia" => utf8_encode($personal["Lugar_Residencia"]),
            "edad" => $personal["Edad"],
            "imagen" => base64_encode($personal["Imagen"]),
            "estado" => utf8_encode($personal["Estado"])
        ];
        }
        
        $observaciones = $conn->prepare("SELECT * FROM Observaciones WHERE DUI =:dui ");
        $result2 = $observaciones->execute([
            "dui" =>$dui
            
        ]);
        $count2 = $conn->prepare("SELECT COUNT(*) FROM Observaciones WHERE DUI =:dui ");
        $count2->execute([
            "dui" =>$dui
            
        ]);
        if($count2->fetchColumn() >=1 && $result2){
            $observacionesJson = [];
            while($observacion = $observaciones->fetch(PDO::FETCH_ASSOC)){
                $observacionesJson[] =[
                    "idObservacion" => $observacion["ID_Observaciones"],
                    "idEmpleado" => $observacion["ID_Empleado"],
                    "dui" => $observacion["DUI"],
                    "observacion" => utf8_encode($observacion["Observacion"]),
                    "fecha" => $observacion["Fecha"],
                    "username" => utf8_encode($observacion["Usuario"])
                ];
            }

            echo json_encode([$json, $observacionesJson]);
        }else{
            echo json_encode([$json, ""]);
        }
    }else{
        echo "No se ha encontrado este empleado";
    }
}else{
    echo "Debe ingresar el número de DUI";
}

?>