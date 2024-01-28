<?php
require 'bd.php';

$clienteId = filter_input(INPUT_GET, 'variable', FILTER_VALIDATE_INT);

if (!$clienteId) {
    die("Variable inválida");
}

$consulta1 = "SELECT clientes.ID, clientes.Nombre, tratamiento.Tratamiento, clientes.Sesiones, clientes.Total_Sesiones FROM clientes INNER JOIN tratamiento ON clientes.ID_Tratamiento = tratamiento.ID WHERE clientes.ID = ?";
$stmt = mysqli_prepare($conexion, $consulta1);

mysqli_stmt_bind_param($stmt, 'i', $clienteId);
mysqli_stmt_execute($stmt);
$resultado1 = mysqli_stmt_get_result($stmt);

while ($fila1 = mysqli_fetch_assoc($resultado1)) {
    $id = $fila1['ID'];
    $nombre = $fila1['Nombre'];
    $tratamiento = $fila1['Tratamiento'];
    $Sesiones = $fila1['Sesiones'];
    $Total_Sesiones = $fila1['Total_Sesiones'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sesiones</title>
</head>
<style>

</style>

<body>
    <div class="contenido">

        <a href="index.php">
            <button class="boton-volver">
                <span class="icono-flecha">&#9664;</span>
            </button>
        </a>

        <figure class="text-center">
            <header>
                <h1><?php echo $nombre ?></h1>
            </header>
            <div class="cuerpo">
                <h5>Tratamiento: <?php echo $tratamiento ?></h5>
                <?php if (isset($Sesiones) == NULL) : ?>
                    <div class="nombre-chico">0 Sesiones</div>
                <?php else : ?>
                    <div class="nombre-chico"><?php echo $Sesiones . "/" . $Total_Sesiones; ?> Sesiones</div>
                <?php endif; ?>
            </div>
        </figure>

        <button class="boton-volver rojo" data-bs-toggle="modal" data-bs-target="#ModalEditar<?php echo $id; ?>">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="ModalEditar<?php echo $id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edicion del Paciente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form" method="POST" action="actualizar_datos.php" name="nuevo_cliente">

                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="form-group">
                                <label for="nombre_paciente" class="col-form-label">
                                    Nombre del Paciente:
                                </label>
                                <input type="text" name="nombre_paciente" id="nombre" value="<?php echo $nombre; ?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="tratamiento_paciente" class="col-form-label">
                                    Tratamiento:
                                </label>
                                <input type="text" name="tratamiento_paciente" id="tratamiento" value="<?php echo $tratamiento; ?>" list="tratamientos" class="form-control" required>

                                <datalist id="tratamientos">
                                    <?php
                                    $mangas = $conexion->query("SELECT DISTINCT Tratamiento FROM `tratamiento`;;;");

                                    foreach ($mangas as $manga) {
                                        echo "<option value='" . $manga['Tratamiento'] . "'></option>";
                                    }

                                    ?>
                                </datalist>

                            </div>

                            <div class="form-group">
                                <label for="sesiones_paciente" class="col-form-label">
                                    Total de Sesiones:
                                </label>
                                <input type="number" name="sesiones_paciente" value="<?php echo $Total_Sesiones; ?>" min="1" max="30" class="form-control" required>

                            </div>




                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php if ($Total_Sesiones > $Sesiones) : ?>
        <figure class="text-center">
            <a href="nueva_sesion.php?variable=<?php echo $id; ?>">
                <button type="button" class="add">
                    <img width="50" height="50" src="./icons/add--v1.png" alt="add--v1" />
                    Nueva Sesion
                </button>
            </a>
        </figure>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql1 = "SELECT * FROM `masajes` where ID_Cliente='$id' ORDER BY `masajes`.`Fecha_Masaje` DESC;";
            $result = mysqli_query($conexion, $sql1);

            while ($mostrar = mysqli_fetch_array($result)) :
            ?>
                <tr>
                    <td><?php echo $mostrar['Fecha_Masaje'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>