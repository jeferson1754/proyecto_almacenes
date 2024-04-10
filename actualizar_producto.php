<?php
include 'bd.php';

date_default_timezone_set('America/Santiago');
$fecha_hora_actual = date('Y-m-d H:i:s');

$id_registro        = $_REQUEST['id'];
$nombre_almacen     = $_REQUEST['nombre_almacen'];
$nombre_producto    = $_REQUEST['nombre_producto'];
$precio             = $_REQUEST['precio'];

echo $nombre_almacen . "<br>" . $nombre_producto . "<br>" . $precio . "<br>";

if ($nombre_almacen != NULL or $nombre_producto != NULL or $precio != NULL) {

    //DATALIST DE ALMACEN
    $almacen = $conexion->query("SELECT * FROM `tiendas` where Nombre_Almacen='$nombre_almacen';");
    $valores = $almacen->fetch_assoc();

    if (!$valores) {
        $sql = "INSERT INTO `tiendas` (`Nombre_Almacen`) VALUES ('$nombre_almacen')";

        if ($conexion->query($sql) === TRUE) {
            $id_almacen = $conexion->insert_id;
        } else {
            // Manejar errores si es necesario
        }
    } else {
        $id_almacen = $valores['ID'];
    }

    //DATALIST DE PRODUCTO
    $producto = $conexion->query("SELECT * FROM `productos` WHERE Nombre='$nombre_producto'");
    $valores2 = $producto->fetch_assoc();

    if (!$valores2) {
        $sql = "INSERT INTO `productos` (`Nombre`) VALUES ('$nombre_producto')";

        if ($conexion->query($sql) === TRUE) {
            $id_producto = $conexion->insert_id;
        } else {
            // Manejar errores si es necesario
        }
    } else {
        $id_producto = $valores2['ID'];
    }

    //INSERTAR DATOS EN REGISTRO DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //UPDATE registro_productos SET ID_Almacen="",ID_Producto="",Valor="",Fecha_Registro="" where ID="";
        $sql = "UPDATE registro_productos SET ID_Almacen='" . $id_almacen . "',ID_Producto='" . $id_producto . "',Valor='" . $precio . "',Fecha_Registro='" . $fecha_hora_actual . "' where ID='" . $id_registro . "';";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    //DATALIST DE PRODUCTO
    $registro = $conexion->query("SELECT * FROM `historial_productos` WHERE ID_Registro='$id_registro'");
    $monto = $registro->fetch_assoc();

    if (!$monto) {
        $diferencia = "0";
    } else {
        $valor_anterior = $monto['Valor'];

        $diferencia = $precio - $valor_anterior;

        echo $valor_anterior . '<br>' . $precio . '<br>' . $diferencia . '<br>';
    }

    //INSERTAR DATOS EN HISTORIAL DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `historial_productos` (`ID`, `ID_Registro`, `Valor`,`Diferencia`,  `Fecha_Historial`)
     VALUES ( NULL ,'" . $id_registro . "','" . $precio . "','" . $diferencia . "','" . $fecha_hora_actual . "')";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
}

/**/

//Redirigir a la página index.html
header("Location: index.php");
exit();
