<?php
include('connection.php');
if((isset($_POST["pago"]) && !empty($_POST["pago"])) && (isset($_POST["descuentos"]) && !empty($_POST["descuentos"])) && (isset($_POST["total"]) && !empty($_POST["total"])) && (isset($_POST["usuario"]) && !empty($_POST["usuario"])) && (isset($_POST["empleado"]) && !empty($_POST["empleado"]))){
if(utf8_decode($_POST["empleado"]) != "Todos"){

$pago = $_POST["pago"];
$descuentos = $_POST["descuentos"];
$total = $_POST["total"];
$username = utf8_decode($_POST["usuario"]);
$empleado = utf8_decode($_POST["empleado"]);

$stmt = $conn->prepare("INSERT INTO Pagos (Nombre_Empleado, Pago, Descuento, Total, Fecha_de_Pago, Usuario, ID_Empleado) VALUES(:nombre, :pago, :descuento, :total, NOW(), :usuario, (SELECT ID from Empleadas WHERE Nombre_Empleada =:nombre))");

$result = $stmt->execute([
    "nombre" => $empleado,
    "pago" => $pago,
    "descuento" => $descuentos,
    "total" => $total,
    "usuario" => $username
]);

if($result){
    $json = ["msg" => "Se ha agregado el pago exitosamente"];
    echo json_encode($json);
}else{
    echo "Ha ocurrido un error al agregar el pago";
}
}else{
    echo "Debe seleccionar una empleada";
}
}else{
    echo "Debe de enviar todos los datos necesarios";
}
?>