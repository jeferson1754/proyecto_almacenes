<?php
include '../bd.php';
include 'permisos.php';

date_default_timezone_set('America/Santiago');
$fecha_hora_actual = date('Y-m-d H:i:s');

$id_registro        = $_REQUEST['id'];
$id_almacen         = $_REQUEST['id_almacen'];
$marca              = $_REQUEST['marca'];
$nombre_producto    = $_REQUEST['nombre_producto'];
$precio             = $_REQUEST['precio'];

// Mostrar los resultados
echo "Nombre del producto: " . $nombre_producto . "<br>";
echo "Marca: " . $marca . "<br>";

if ($id_almacen != NULL or $id_producto != NULL or $precio != NULL) {

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

    echo "ID Producto: " . $id_producto . "<br>";

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

    //ACTUALIZAR MARCA EN  PRODUCTOS
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


    //ACTUALIZAR DATOS EN REGISTRO DE PRODUCTOS
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$basededatos", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE registro_productos SET ID_Almacen='" . $id_almacen . "',ID_Producto='" . $id_producto . "',Valor='" . $precio . "',Fecha_Registro='" . $fecha_hora_actual . "' where ID='" . $id_registro . "';";
        $conn->exec($sql);
        echo $sql . "<br>";
        $conn = null;
    } catch (PDOException $e) {
        $conn = null;
    }

    //HISTORIAL DE PRODUCTO
    $registro = $conexion->query("SELECT * FROM `historial_productos` WHERE ID_Registro='$id_registro'");
    $monto = $registro->fetch_assoc();

    if (!$monto) {
        $diferencia = "0";
    } else {
        $valor_anterior = $monto['Valor'];

        $diferencia = $precio - $valor_anterior;

        echo "Valor Anterior:" . $valor_anterior . '<br> Precio Actual:' . $precio . '<br> Diferencia:' . $diferencia . '<br>';
    }

    if ($diferencia != 0) {
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
}

/**/

//Redirigir a la página index.html
header("Location: tienda_admin.php?id=$id_almacen");
exit();
