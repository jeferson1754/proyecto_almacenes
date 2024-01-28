<?php
require 'bd.php';

date_default_timezone_set('America/Santiago');

$fecha_hora_actual = date('Y-m-d H:i:s');

if (isset($_GET['variable'])) {
    $variable = $_GET['variable'];

    $consulta1 = "SELECT ID, ID_Tratamiento FROM `clientes` WHERE ID=?";
    $stmt = mysqli_prepare($conexion, $consulta1);
    mysqli_stmt_bind_param($stmt, 'i', $variable);
    mysqli_stmt_execute($stmt);
    $resultado1 = mysqli_stmt_get_result($stmt);

    while ($fila1 = mysqli_fetch_assoc($resultado1)) {
        $id_cliente = $fila1['ID'];
        $id_tratamiento = $fila1['ID_Tratamiento'];
    }

    $stmt->close();

    $nuevoMasaje = $conexion->prepare("INSERT INTO `masajes`(`ID_Cliente`, `ID_Tratamiento`, `Fecha_Masaje`) VALUES (?, ?, ?)");
    $nuevoMasaje->bind_param('iss', $id_cliente, $id_tratamiento, $fecha_hora_actual);
    $nuevoMasaje->execute();
    $nuevoMasaje->close();

    $updateSesiones = $conexion->query("UPDATE clientes SET Sesiones = (SELECT COUNT(*) FROM masajes WHERE ID_Cliente ='$id_cliente') WHERE ID = '$id_cliente'");

    header("Location:sesiones.php?variable=$id_cliente");
    exit();
} else {
    header("Location:index.php");
    exit();
}
