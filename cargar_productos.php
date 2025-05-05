<?php
// Include database connection
require 'bd.php';

// Define columns to retrieve from database
$columns = ['a.Nombre_Almacen', 'p.Nombre', 'rp.Valor', 'a.ID'];

// Safely get search term from POST data
$campo = isset($_POST['campo']) ? $conexion->real_escape_string($_POST['campo']) : null;

// Initialize the WHERE clause
$where = "";

// If search term exists, build WHERE clause for searching across columns
if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);

    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }

    // Remove trailing 'OR ' and close the parenthesis
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

// Build the SQL query with proper table aliases
$sql = "SELECT " . implode(",", $columns) . " 
        FROM registro_productos rp 
        INNER JOIN tiendas a ON rp.ID_Almacen = a.ID 
        INNER JOIN productos p ON rp.ID_Producto = p.ID 
        " . $where;

// Add additional conditions if WHERE clause already exists
if ($where != "") {
    $sql .= " AND p.ID != 3";
} else {
    $sql .= " WHERE p.ID != 3";
}

// Add order by clause
$sql .= " ORDER BY rp.ID DESC, rp.Valor ASC";

// Execute the query
$resultado = $conexion->query($sql);

// Check for query execution errors
if (!$resultado) {
    // Return plain HTML for error
    echo '<div class="col-12 text-center">
            <div class="alert alert-danger mt-5 mb-5">Error en la consulta: ' . $conexion->error . '</div>
          </div>';
    exit;
}

$num_rows = $resultado->num_rows;
$html = '';

// Build HTML based on results
if ($num_rows > 0) {
    while ($mostrar = $resultado->fetch_assoc()) {
        $html .= '<div class="col-md-12 mb-4">';
        $html .= '<div class="card product-card animate__animated animate__fadeIn">';
        $html .= '<div class="product-image">';
        $html .= '<i class="fas fa-box"></i>';
        $html .= '</div>';
        $html .= '<div class="product-details">';
        $html .= '<h5 class="product-title">' . htmlspecialchars($mostrar['Nombre']) . '</h5>';
        $html .= '<p class="product-price">$' . number_format($mostrar['Valor'], 0, ',', '.') . '</p>';
        $html .= '<p class="store-address">' . htmlspecialchars($mostrar['Nombre_Almacen']) . '</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    }
} else {
    $html .= '<div class="col-12 text-center">';
    $html .= '<h3 class="mt-5 mb-5">No se encontraron resultados</h3>';
    $html .= '</div>';
}

// Return direct HTML instead of JSON
echo $html;
