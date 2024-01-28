<?php
include 'bd.php';

$id_cliente     = $_REQUEST['id'];
$nombre         = $_REQUEST['nombre_paciente'];
$tratamiento    = $_REQUEST['tratamiento_paciente'];
$total_sesiones = $_REQUEST['sesiones_paciente'];

echo "$nombre<br>$tratamiento<br>$total_sesiones<br>";

// DATALIST DE TRATAMIENTO
$masaje = $conexion->query("SELECT ID FROM `tratamiento` WHERE Tratamiento='$tratamiento'");
$valores2 = $masaje->fetch_assoc();

if (!$valores2) {
    $conexion->query("INSERT INTO `tratamiento` (`Tratamiento`) VALUES ('$tratamiento')");
    $id_tratamiento = $conexion->insert_id;
} else {
    $id_tratamiento = $valores2['ID'];
}

// ACTUALIZAR DATOS EN CLIENTES
try {
    $sql = "UPDATE `clientes` SET `Nombre`='$nombre', `ID_Tratamiento`='$id_tratamiento', `Total_Sesiones`='$total_sesiones' WHERE `ID`='$id_cliente'";
    mysqli_query($conexion, $sql);
    echo "$sql<br>";
} catch (Exception $e) {
    echo "$sql<br>";
    echo $e->getMessage();
}

$conexion->query("UPDATE clientes SET Sesiones = (SELECT COUNT(*) FROM masajes WHERE ID_Cliente ='$id_cliente') WHERE ID = '$id_cliente'");

// Redirigir a la página index.html
header("Location:sesiones.php?variable=$id_cliente");
exit();
