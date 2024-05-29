<?php
include 'bd.php';


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
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluir Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Incluir Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Tiendas</title>
</head>

<body>

    <div class="menu-container">
        <div class="menu-options">
            <div class="menu-option" onclick="showOption(1)">
                Productos
                <div class="menu-line display" id="linea-option-1"></div>
            </div>
            <div class="menu-option" onclick="showOption(5)">
                Pan
                <div class="menu-line" id="linea-option-5"></div>
            </div>
            <div class="menu-option" onclick="showOption(2)">
                Almacenes
                <div class="menu-line" id="linea-option-2"></div>
            </div>
            <div class="menu-option" onclick="showOption(3)">
                Supermercados
                <div class="menu-line" id="linea-option-3"></div>
            </div>
            <div class="menu-option">
                <a href="./login.html">Login</a>
                <div class="menu-line" id="linea-option-4"></div>
            </div>

        </div>
    </div>


    <div class="info-container active" id="info-option-1">


        <figure class="text-center">
            <h1>Lista de Productos</h1>
        </figure>
        <!-- Button trigger modal -->

        <div class="ocultar">
            <div class="d-flex justify-content-center">
                <button type="button" class="button-plus" data-bs-toggle="modal" data-bs-target="#ModalNuevo">
                    <i class="fa-solid fa-plus"></i>
                </button>

                <button type="button" class="button-plus filtrar" onclick="toggleForm()">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="button" class="button-plus mostrar" data-bs-toggle="modal" data-bs-target="#ModalNuevo">
                <i class="fa-solid fa-plus"></i>
            </button>

            <form action="" method="POST" class="search-form">
                <input type="search" name="campo" id="campo" placeholder="Buscar...">
            </form>

            <button type="button" class="button-plus filtrar mostrar" onclick="toggleForm()">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>

        <div class="justify-content-center">
            <form action="categorias.php" method="POST" id="filtro" class="search-form ocultar">
                <select name="filtro" id="filtro" onchange="this.form.submit()">
                    <option value="">Seleccione</option>
                    <?php
                    $categorias = $conexion->query("SELECT * FROM `categorias`;");

                    foreach ($categorias as $categoria) {
                        echo "<option value='" . $categoria['ID'] . "'>" . $categoria['Nombre'] . "</option>";
                    }

                    ?>
                </select>
            </form>
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
                                    $tiendas = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `tiendas`;");

                                    foreach ($tiendas as $tienda) {
                                        echo "<option value='" . $tienda['Nombre_Almacen'] . "'></option>";
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
                                    $productos = $conexion->query("SELECT CONCAT(productos.Nombre, ' - ', marca.Nombre) AS Nombre FROM productos INNER JOIN marca ON marca.ID = productos.ID_Marca;");

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

        <div id="content" class="deudores">


        </div>


    </div>

    <div class="info-container" id="info-option-5">
        <figure class="text-center">
            <h1>Pan</h1>
        </figure>
        <div class="deudores">
            <?php
            $sql1 = "SELECT rp.ID,Nombre_Almacen,Nombre,Valor,(a.ID) as ID_Almacen FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID WHERE p.ID = 3 ORDER BY `rp`.`Valor` ASC;";
            //echo $sql1;
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>

                <div class="persona-container">

                    <div class="nombre-persona"><?php echo $mostrar2['Nombre'] . '<br> $' . $mostrar2['Valor'] ?></div>

                    <div class="nombre-chico"><?php echo $mostrar2['Nombre_Almacen'] ?></div>


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
            <button type="button" class="button-plus filtrar mostrar" onclick="toggleForm2()">
                <i class="fa-solid fa-filter"></i>
            </button>

        </div>
        <div class="ocultar">
            <div class="d-flex justify-content-center">
                <button type="button" class="button filtrar extendido" onclick="toggleForm2()">
                    <i class="fa-solid fa-filter"></i> Filtrar Almacenes
                </button>
            </div>
        </div>
        <div id="contenedor_almacen" class="justify-content-center" style="display:none;">
            <select class="js-example-basic-multiple" name="filtro_almacen[]" id="filtro_almacen" multiple="multiple" style="width: 100%;">
                <option value="acepta_tarjetas">Acepta Tarjetas</option>
                <option value="caja_vecina">Caja Vecina</option>
                <option value="carga_bip">Carga Bip</option>
                <option value="vende_cigarros">Vende Cigarros</option>
                <option value="vende_alcohol">Vende Alcohol</option>
            </select>
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

        <div class="deudores" id="tiendas_almacenes">
            <?php
            $sql1 = "SELECT t.*, COUNT(rp.ID) AS total_productos FROM tiendas t LEFT JOIN registro_productos rp ON t.ID = rp.ID_Almacen WHERE t.Tipo = 'Almacen' GROUP BY t.ID ORDER BY total_productos DESC;";
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>
                <div class="persona-container">
                    <div class="circle-container">
                        <div class="circle"><?php echo $mostrar2['total_productos'] ?></div>
                    </div>
                    <?php
                    $variable_id = $mostrar2["ID"]; ?>
                    <a href="tienda.php?id=<?php echo $variable_id; ?>">
                        <div class="nombre-persona"><?php echo $mostrar2['Nombre_Almacen'] ?></div>
                    </a>

                    <div class="nombre-chico">
                        <?php if ($mostrar2['Direccion'] != NULL) {
                            echo $mostrar2['Direccion'];
                        } else {
                            echo "Sin Direccion";
                        } ?>
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
            $sql1 = "SELECT t.*, COUNT(rp.ID) AS total_productos FROM tiendas t LEFT JOIN registro_productos rp ON t.ID = rp.ID_Almacen WHERE t.Tipo = 'Supermercado' GROUP BY t.ID ORDER BY total_productos DESC;";
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>
                <div class="persona-container">
                    <div class="circle-container">
                        <div class="circle"><?php echo $mostrar2['total_productos'] ?></div>
                    </div>
                    <?php
                    $variable_id = $mostrar2["ID"]; ?>
                    <a href="tienda.php?id=<?php echo $variable_id; ?>">
                        <div class="nombre-persona"><?php echo $mostrar2['Nombre_Almacen'] ?></div>
                    </a>
                    <div class="nombre-chico">
                        <?php if ($mostrar2['Direccion'] != NULL) {
                            echo $mostrar2['Direccion'];
                        } else {
                            echo "Sin Direccion";
                        } ?>
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
            if (option === selectedOption) {
                return; // Salir si la opción seleccionada ya está activa
            }

            // Ocultar la información de la opción anterior y su línea
            hideInfoAndLine(selectedOption);

            // Mostrar la información de la opción seleccionada y su línea
            showInfoAndLine(option);

            selectedOption = option;
        }

        function hideInfoAndLine(option) {
            const prevInfoContainer = document.querySelector(`#info-option-${option}`);
            prevInfoContainer.classList.remove('active');

            const menuprev = document.querySelector(`#linea-option-${option}`);
            menuprev.classList.remove('display');
        }

        function showInfoAndLine(option) {
            const currentInfoContainer = document.querySelector(`#info-option-${option}`);
            currentInfoContainer.classList.add('active');

            const menuok = document.querySelector(`#linea-option-${option}`);
            menuok.classList.add('display');
        }



        // Al cargar la página, mostrar la primera opción por defecto
        showOption(selectedOption);

        getData();

        document.getElementById("campo").addEventListener("keyup", getData)

        function getData() {
            let input = document.getElementById("campo").value;
            let content = document.getElementById("content");
            let url = "load.php";
            let formaData = new FormData();
            formaData.append('campo', input);

            fetch(url, {
                    method: "POST",
                    body: formaData
                }).then(response => response.json())

                .then(data => {
                    content.innerHTML = data
                }).catch(e => console.log(e))
        }

        function toggleForm() {
            var form = document.getElementById("filtro");
            form.classList.toggle("ocultar");
        }

        function toggleForm2() {
            var form = document.getElementById("contenedor_almacen");
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: 'Selecciona características',
                allowClear: true
            });

            $('#filtro_almacen').on('change', function() {
                var filtros = $(this).val();
                $.ajax({
                    url: 'filtro_tiendas.php',
                    type: 'POST',
                    data: {
                        filtros: filtros
                    },
                    success: function(response) {
                        $('#tiendas_almacenes').html(response);
                    }
                });
            });
        });
    </script>
</body>

</html>