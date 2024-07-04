<?php
include '../bd.php';
include 'permisos.php';

date_default_timezone_set('America/Santiago');
$fecha_hora_actual = date('Y-m-d H:i:s');

$id_almacen        = $_REQUEST['id'];
$nombre_almacen    = $_REQUEST['nombre_almacen'];
$direccion         = $_REQUEST['direccion'];
$link_imagen       = $_REQUEST['link_imagen'];
$detalles          = $_REQUEST['detalles'];


echo $nombre_almacen . "<br>" . $direccion . "<br>" . $link_imagen . "<br>Detalle: " . $detalles . "<br>";

if ($nombre_almacen != NULL or $direccion != NULL or $detalles != NULL) {

    //ACTUALIZAR DATOS EN REGISTRO DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE tiendas SET Nombre_Almacen='" . $nombre_almacen . "',Direccion='" . $direccion . "',Link_Imagen='" . $link_imagen . "',Detalles='" . $detalles . "' where ID='" . $id_almacen . "';";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
}

/**/

//Redirigir a la página index.html
header("Location: tienda_admin.php?id=$id_almacen");
exit();
