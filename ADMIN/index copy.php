<?php
include '../bd.php';
require 'permisos.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/checkbox.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin-Tiendas</title>
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
        <table id="example" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Valor</th>
                    <th>Almacen</th>
                    <th>Fecha</th>

                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql1 = "SELECT rp.ID, a.Nombre_Almacen, p.Nombre, m.Nombre AS Marca, rp.Valor, a.ID AS ID_Almacen, rp.Fecha_Registro FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID INNER JOIN marca m ON p.ID_Marca = m.ID ORDER BY rp.ID DESC limit 100;";
                $result1 = mysqli_query($conexion, $sql1);

                while ($mostrar2 = mysqli_fetch_array($result1)) {
                ?>
                    <tr>
                        <td><?php echo $mostrar2['Nombre'] ?></td>
                        <td><?php echo $mostrar2['Marca'] ?></td>
                        <td>$<?php echo $mostrar2['Valor'] ?></td>
                        <td><a href="tienda_admin.php?id=<?php echo $mostrar2["ID_Almacen"]; ?>" target="_blanck"><?php echo $mostrar2['Nombre_Almacen'] ?></a></td>
                        <td><?php echo $mostrar2['Fecha_Registro'] ?></td>
                        <td>
                            <button class="boton-volver" data-bs-toggle="modal" data-bs-target="#ModalEditarProducto<?php echo $mostrar2['ID']; ?>">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <?php
                            $variable = $mostrar2['ID'];
                            ?>
                            <a href="historial_producto.php?variable=<?php echo urlencode($variable); ?>" target="_blanck">
                                <button type="button" class="boton-volver gray">
                                    <i class="fa fa-bar-chart"></i>
                                </button>
                            </a>

                            <button class="boton-volver red" data-bs-toggle="modal" data-bs-target="#Delete<?php echo $mostrar2['ID']; ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>

                <?php
                    include 'ModalEditar.php';
                    include 'ModalDelete.php';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

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
        $(document).ready(function() {
            $('#example').DataTable({
                    "order": [],
                    language: {
                        processing: "Tratamiento en curso...",
                        search: "Buscar:",
                        lengthMenu: "Filtro de _MENU_Productos",
                        info: "Mostrando Productos del _START_ al _END_ de un total de _TOTAL_ Productos",
                        infoEmpty: "No existen registros",
                        infoFiltered: "(filtrado de _MAX_ Productos en total)",
                        infoPostFix: "",
                        loadingRecords: "Cargando elementos...",
                        zeroRecords: "No se encontraron los datos de tu busqueda..",
                        emptyTable: "No hay ningun registro en la tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Ultimo"
                        },
                        aria: {
                            sortAscending: ": Active para odernar en modo ascendente",
                            sortDescending: ": Active para ordenar en modo descendente  ",
                        }
                    }


                }


            );

        });
    </script>
</body>

</html>