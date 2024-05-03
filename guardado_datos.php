<?php
include 'bd.php';

date_default_timezone_set('America/Santiago');
$fecha_hora_actual = date('Y-m-d H:i:s');

$nombre_almacen     = $_REQUEST['nombre_almacen'];
$nombre_producto    = $_REQUEST['nombre_producto'];
$precio             = $_REQUEST['precio'];

// Dividir la cadena en dos partes usando el carácter "-"
$partes = explode(" - ", $nombre_producto);

// La primera parte será el nombre del producto
$nombre_producto = $partes[0];

// La segunda parte será la marca
$marca = $partes[1];

// Mostrar los resultados
echo "Nombre del producto: " . $nombre_producto . "<br>";
echo "Marca: " . $marca . "<br>";

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

    if ($marca != NULL) {
        //DATALIST DE MARCA
        $marca2 = $conexion->query("SELECT * FROM `marca` WHERE Nombre='$marca'");
        $valores3 = $marca2->fetch_assoc();

        if (!$valores3) {
            $sql = "INSERT INTO `marca` (`Nombre`) VALUES ('$marca')";

            if ($conexion->query($sql) === TRUE) {
                $id_marca = $conexion->insert_id;
            } else {
                // Manejar errores si es necesario
            }
        } else {
            $id_marca = $valores3['ID'];
        }
    } else if ($marca == "") {
        $id_marca = 1;
    }

    echo "ID Marca: " . $id_marca . "<br>";

    //INSERTAR MARCA EN  PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE productos SET ID_Marca='" . $id_marca . "' where ID='" . $id_producto . "';";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    //INSERTAR DATOS EN REGISTRO DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `registro_productos` (`ID`, `ID_Almacen`, `ID_Producto`, `Valor`, `Fecha_Registro`)
 VALUES ( NULL ,'" . $id_almacen . "','" . $id_producto . "','" . $precio . "','" . $fecha_hora_actual . "')";
        $conn->exec($sql);
        $last_id_registro_productos = $conn->lastInsertId();
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    //INSERTAR DATOS EN HISTORIAL DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `historial_productos` (`ID`, `ID_Registro`, `Valor`, `Fecha_Historial`)
     VALUES ( NULL ,'" . $last_id_registro_productos . "','" . $precio . "','" . $fecha_hora_actual . "')";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }
}


//Redirigir a la página index.html
header("Location: index.php");
exit();
