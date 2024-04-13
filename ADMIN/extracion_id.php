<?php
include '../bd.php';

$sql1 = "SELECT rp.ID FROM registro_productos rp ORDER BY rp.ID DESC;";
$result1 = mysqli_query($conexion, $sql1);

$tusDatos = array(); // Inicializar un array para almacenar los IDs de los registros

while ($mostrar2 = mysqli_fetch_array($result1)) {
    // Agregar el ID actual a la matriz $tusDatos
    $tusDatos[] = $mostrar2['ID'];
}

// Salida JSON de los IDs de los registros
echo json_encode($tusDatos);
?>
