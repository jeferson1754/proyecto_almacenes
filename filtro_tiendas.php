<?php
include 'bd.php';

$filtros = isset($_POST['filtros']) ? $_POST['filtros'] : [];

$sql = "SELECT t.*, COUNT(rp.ID) AS total_productos FROM tiendas t LEFT JOIN registro_productos rp ON t.ID = rp.ID_Almacen WHERE t.Tipo = 'Almacen'";

if (!empty($filtros)) {
    $condiciones = [];
    foreach ($filtros as $filtro) {
        $condiciones[] = "t.Detalles LIKE '%" . mysqli_real_escape_string($conexion, $filtro) . "%'";
    }
    if (count($condiciones) > 0) {
        $sql .= " AND " . implode(" AND ", $condiciones) . "";
    }
}

$sql .= " GROUP BY t.ID ORDER BY total_productos DESC;";
$result = mysqli_query($conexion, $sql);
//echo $sql;

while ($mostrar2 = mysqli_fetch_array($result)) {
?>
    <div class="persona-container">
        <div class="circle-container">
            <div class="circle">
                <?php echo $mostrar2['total_productos'] ?>
            </div>
        </div>
        <?php
        $variable_id = $mostrar2["ID"]; ?>
        <a href="tienda.php?id=<?php echo $variable_id; ?>">
            <div class="nombre-persona">
                <?php echo $mostrar2['Nombre_Almacen'] ?>
            </div>
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