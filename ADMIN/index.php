<?php
include '../bd.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/checkbox.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tiendas</title>
</head>

<body>

    <div class="menu-container">
        <div class="menu-options">
            <div class="menu-option">
                <a href="../index.php">Volver</a>
            </div>
            <div class="menu-option" onclick="showOption(1)">
                Editar Productos
                <div class="menu-line display" id="linea-option-1"></div>
            </div>
        </div>
    </div>

    <div class="info-container active" id="info-option-1">
        <figure class="text-center">
            <h1>Lista de Productos</h1>
        </figure>

        <div class="deudores">
            <?php
            $sql1 = "SELECT rp.ID,Nombre_Almacen,Nombre,Valor FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID ORDER BY `rp`.`ID` DESC;";
            $result1 = mysqli_query($conexion, $sql1);

            while ($mostrar2 = mysqli_fetch_array($result1)) {
            ?>

                <div class="persona-container">

                    <div class="nombre-persona"><?php echo $mostrar2['Nombre'] . '<br> $' . $mostrar2['Valor'] ?></div>
                    <div class="nombre-chico">
                        <?php echo $mostrar2['Nombre_Almacen'] ?>
                    </div>
                    <div class="contenido">
                        <button class="boton-volver" data-bs-toggle="modal" data-bs-target="#ModalEditarProducto<?php echo $mostrar2['ID']; ?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </div>
                </div>


            <?php
                include 'ModalEditar.php';
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
    </script>
</body>

</html>