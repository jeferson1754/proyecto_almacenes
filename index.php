<?php
include 'bd.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Almacenes</title>
</head>

<body>
    <figure class="text-center">
        <h1>Lista de Productos</h1>
    </figure>
    <!-- Button trigger modal -->
    <div class="d-flex justify-content-center">
        <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#ModalNuevo">
            Nuevo Producto
        </button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ModalNuevo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Deudor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="guardado_datos.php">
                        <div class="form-group">
                            <label for="nombre_almacen" class="col-form-label">
                                Nombre del Almacen:
                            </label>
                            <input type="text" name="nombre_almacen" id="nombre" list="nombres" class="form-control" required>
                            <datalist id="nombres">
                                <?php
                                $nombres = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `almacenes`;;");

                                foreach ($nombres as $nombre) {
                                    echo "<option value='" . $nombre['Nombre_Almacen'] . "'></option>";
                                }

                                ?>
                            </datalist>
                        </div>


                        <div class="form-group">
                            <label for="nombre_producto" class="col-form-label">
                                Nombre del Producto:
                            </label>
                            <input type="text" name="nombre_producto" id="producto" list="productos" class="form-control" required>
                            <datalist id="productos">
                                <?php
                                $productos = $conexion->query("SELECT DISTINCT Nombre FROM `productos`;");

                                foreach ($productos as $producto) {
                                    echo "<option value='" . $producto['Nombre'] . "'></option>";
                                }

                                ?>
                            </datalist>
                        </div>


                        <div class="form-group">
                            <label for="precio" class="col-form-label">
                                Precio del Producto:
                            </label>
                            <input type="number" name="precio" value="0" min="10" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="deudores">
        <?php
        $sql = "SELECT rp.ID, a.Nombre_Almacen, p.Nombre, rp.Valor FROM registro_productos rp INNER JOIN almacenes a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID;";
        $result = mysqli_query($conexion, $sql);

        while ($mostrar = mysqli_fetch_array($result)) {
        ?>
            <div class="persona-container">

                <div class="nombre-persona"><?php echo $mostrar['Nombre'] . "<br> $" . $mostrar['Valor'] ?></div>
                <div class="nombre-chico">
                    <?php echo $mostrar['Nombre_Almacen'] ?>
                </div>
                <div class="contenido">
                    <button class="boton-volver" data-bs-toggle="modal" data-bs-target="#ModalEditar<?php echo $mostrar['ID']; ?>">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>