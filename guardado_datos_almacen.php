<?php
include 'bd.php';

date_default_timezone_set('America/Santiago');
$fecha_hora_actual = date('Y-m-d H:i:s');

$nombre_almacen     = $_REQUEST['nombre_almacen'];
$direccion         = $_REQUEST['direccion_almacen'];

echo $nombre_almacen . "<br>" . $direccion . "<br>";

//DATALIST DE ALMACEN
$almacen = $conexion->query("SELECT * FROM `almacenes` where Nombre_Almacen='$nombre_almacen';");
$valores = $almacen->fetch_assoc();

if (!$valores) {
    $sql = "INSERT INTO `almacenes` (`Nombre_Almacen`,`Direccion`) VALUES ('$nombre_almacen','$direccion')";

    if ($conexion->query($sql) === TRUE) {
        $id_almacen = $conexion->insert_id;
    } else {
        // Manejar errores si es necesario
    }
} else {
    $id_almacen = $valores['ID'];
}


//Redirigir a la página index.html
header("Location: index.php");
exit();