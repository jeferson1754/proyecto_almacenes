<?php
include 'bd.php';
$categoria = $_POST['filtro'];

$categorias = $conexion->query("SELECT * FROM `categorias` where ID='$categoria';");
$valores = $categorias->fetch_assoc();
if (!$valores) {
    $nombre_categoria = "Todos";
} else {
    $nombre_categoria = $valores['Nombre'];
}
$productos = $conexion->query("SELECT a.Nombre_Almacen,p.Nombre,rp.Valor FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID WHERE p.ID_Categoria='$categoria' AND p.ID !=3 ORDER BY `rp`.`Fecha_Registro` DESC;");
$conteo_total = $productos->num_rows;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/checkbox.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Categorias</title>
</head>

<body>
    <div class="info-container active">
        <figure class="text-center">
            <h1><?php echo $nombre_categoria . "-" . $conteo_total; ?></h1>
        </figure>

        <div class="deudores">
            <?php


            while ($producto = mysqli_fetch_array($productos)) {
            ?>

                <div class="persona-container">

                    <div class="nombre-persona"><?php echo $producto['Nombre'] . '<br> $' . $producto['Valor'] ?></div>

                    <div class="nombre-chico"><?php echo $producto['Nombre_Almacen'] ?></div>


                </div>


            <?php
            }

            ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>