<?php
include '../bd.php';
include 'permisos.php';

$id_registro        = $_REQUEST['id'];


//ACTUALIZAR DATOS EN REGISTRO DE PRODUCTOS
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM registro_productos  where ID='" . $id_registro . "';";
    $conn->exec($sql);
    echo $sql . "<br>";
    $conn = null;
} catch (PDOException $e) {
    $conn = null;
}


//Redirigir a la página index.html
header("Location: index.php");
exit();
