<?php
// Include database connection
require 'bd.php';

// Definir columnas para la búsqueda
$columns = ['a.Nombre_Almacen', 'p.Nombre', 'rp.Valor', 'a.ID'];

// Obtener datos de GET (porque el AJAX los envía como GET)
$campo = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : null;
$filtro = isset($_GET['filtro']) ? $conexion->real_escape_string($_GET['filtro']) : null;

// Inicializar WHERE
$where = "WHERE p.ID != 3";

// Si hay búsqueda
if ($campo != null && $campo !== '') {
    $where .= " AND (";
    foreach ($columns as $col) {
        $where .= "$col LIKE '%$campo%' OR ";
    }
    $where = substr($where, 0, -4); // eliminar último " OR "
    $where .= ")";
}

// Si hay filtro por categoría
if ($filtro != null && $filtro !== '') {
    $where .= " AND p.ID_Categoria = '$filtro'";
}

// Consulta SQL
// Consulta SQL
$sql = "SELECT p.Nombre, MIN(rp.Valor) AS Valor, p.ID
        FROM registro_productos rp 
        INNER JOIN tiendas a ON rp.ID_Almacen = a.ID 
        INNER JOIN productos p ON rp.ID_Producto = p.ID 
        $where
        GROUP BY p.Nombre
        ORDER BY rp.ID DESC, valor;";

$resultado = $conexion->query($sql);

// Manejo de errores
if (!$resultado) {
    echo '<div class="col-12 text-center">
            <div class="alert alert-danger mt-5 mb-5">Error en la consulta: ' . $conexion->error . '</div>
          </div>';
    exit;
}

$num_rows = $resultado->num_rows;
$html = '';

if ($num_rows > 0) {
    $html .= '<div class="product-grid ">';
    while ($mostrar = $resultado->fetch_assoc()) {
        $html .= '<div class="col-md-12 mb-4">';
        $html .= '<div class="card product-card animate__animated animate__fadeIn">';
        $html .= '<div class="product-image">';
        $html .= '<i class="fas fa-box"></i>';
        $html .= '</div>';
        $html .= '<div class="product-details">';
        $html .= '<h5 class="product-title">' . htmlspecialchars($mostrar['Nombre']) . '</h5>';
        $html .= '<p class="product-price">$' . number_format($mostrar['Valor'], 0, ',', '.') . '</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    $html .= '</div>';
} else {
    $html .= '<div class="d-flex justify-content-center">';
    $html .= '<h3 class="mt-5 mb-5 text-center">No se encontraron resultados</h3>';
    $html .= '</div>';
}

echo $html;
