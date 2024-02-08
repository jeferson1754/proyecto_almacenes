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
    <title>Tiendas</title>
</head>

<body>


    <div class="menu-container">
        <div class="menu-options">
            <div class="menu-option" onclick="showOption(1)">
                Productos
                <div class="menu-line"></div>
            </div>
            <div class="menu-option" onclick="showOption(2)">
                Almacenes
            </div>
            <div class="menu-option" onclick="showOption(3)">
                Supermercados
            </div>
        </div>
    </div>

    <div class="info-container active" id="info-option-1">
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
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Productos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form" method="POST" action="guardado_datos.php">
                            <div class="form-group">
                                <label for="nombre_almacen" class="col-form-label">
                                    Nombre de la Tienda:
                                </label>
                                <input type="text" name="nombre_almacen" id="nombre" list="nombres" class="form-control" required>
                                <datalist id="nombres">
                                    <?php
                                    $nombres = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `tiendas`;");

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
            $sql = "SELECT rp.ID, a.Nombre_Almacen, p.Nombre, rp.Valor FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID ORDER BY `rp`.`ID` DESC;";
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
    </div>

    <div class="info-container" id="info-option-2">
        <figure class="text-center">
            <h1>Lista de Almacenes</h1>
        </figure>
        <!-- Button trigger modal -->
        <div class="d-flex justify-content-center">
            <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#ModalNuevoAlmacen">
                Nuevo Almacen
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="ModalNuevoAlmacen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Almacenes</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form" method="POST" action="guardado_datos_almacen.php">
                            <input type="hidden" name="tipo" value="Almacen">
                            <div class="form-group">
                                <label for="nombre_almacen" class="col-form-label">
                                    Nombre del Almacen:
                                </label>
                                <input type="text" name="nombre_almacen" id="nombres" list="nombre_al" class="form-control" required>
                                <datalist id="nombre_al">
                                    <?php
                                    $nombres = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `tiendas` where Tipo='Almacen';");

                                    foreach ($nombres as $nombre) {
                                        echo "<option value='" . $nombre['Nombre_Almacen'] . "'></option>";
                                    }

                                    ?>
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label for="direccion_almacen" class="col-form-label">
                                    Direccion del Almacen:
                                </label>
                                <input type="text" name="direccion_almacen" id="direccion" class="form-control" required>
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
            $sql1 = "SELECT * FROM `tiendas` where Tipo='Almacen';";
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>
                <div class="persona-container">

                    <div class="nombre-persona"><?php echo $mostrar2['Nombre_Almacen'] ?></div>
                    <div class="nombre-chico">
                        <?php echo $mostrar2['Direccion'] ?>
                    </div>
                    <div class="contenido">
                        <button class="boton-volver" data-bs-toggle="modal" data-bs-target="#ModalEditar<?php echo $mostrar2['ID']; ?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="info-container" id="info-option-3">
        <figure class="text-center">
            <h1>Lista de Supermercados</h1>
        </figure>
        <!-- Button trigger modal -->
        <div class="d-flex justify-content-center">
            <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#ModalNuevoSupermercado">
                Nuevo Supermercado
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="ModalNuevoSupermercado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Supermercados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form" method="POST" action="#">
                            <input type="hidden" name="tipo" value="Supermercado">
                            <div class="form-group">
                                <label for="nombre_supermercado" class="col-form-label">
                                    Nombre del Supermercado:
                                </label>
                                <input type="text" name="nombre_supermercado" id="nombres" list="nombre_sup" class="form-control" required>
                                <datalist id="nombre_sup">
                                    <?php
                                    $nombres = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `tiendas` where Tipo='Supermercado';");

                                    foreach ($nombres as $nombre) {
                                        echo "<option value='" . $nombre['Nombre_Almacen'] . "'></option>";
                                    }

                                    ?>
                                </datalist>
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
            $sql1 = "SELECT * FROM `tiendas` where Tipo='Supermercado';";
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>
                <div class="persona-container">

                    <div class="nombre-persona"><?php echo $mostrar2['Nombre_Almacen'] ?></div>
                    <div class="nombre-chico">
                        <?php echo $mostrar2['Direccion'] ?>
                    </div>
                    <div class="contenido">
                        <button class="boton-volver" data-bs-toggle="modal" data-bs-target="#ModalEditar<?php echo $mostrar2['ID']; ?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedOption = 1;

        function showOption(option) {
            if (option !== selectedOption) {
                // Mover la línea debajo de la opción seleccionada
                const menuLine = document.querySelector('.menu-line');
                menuLine.style.transform = `translateX(${(option - 1) * 128}%)`;

                // Ocultar la información de la opción anterior
                const prevInfoContainer = document.querySelector(`#info-option-${selectedOption}`);
                prevInfoContainer.classList.remove('active');

                // Mostrar la información de la opción seleccionada
                const currentInfoContainer = document.querySelector(`#info-option-${option}`);
                currentInfoContainer.classList.add('active');

                selectedOption = option;
            }
        }

        // Al cargar la página, mostrar la primera opción por defecto
        showOption(selectedOption);
    </script>
</body>

</html>