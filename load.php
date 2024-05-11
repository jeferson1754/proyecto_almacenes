<?php

require 'bd.php';

$columns = ['Nombre_Almacen', 'Nombre', 'Valor', 'a.ID'];


$campo = isset($_POST['campo']) ? $conexion->real_escape_string($_POST['campo']) : null;

$where = "";

if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);

    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }

    $where = substr_replace($where, "", -3);

    $where .= ")";
}



$sql = "SELECT " . implode(",", $columns) . " FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID $where AND p.ID !=3 ORDER BY `rp`.`ID` DESC, `rp`.`Valor` ASC;";
//echo $sql;

$resultado = $conexion->query($sql);

$num_rows = $resultado->num_rows;

$html = '';

if ($num_rows > 0) {

    while ($mostrar = mysqli_fetch_array($resultado)) {

        $html .= '<div class="persona-container">';
        $html .= '<div class="nombre-persona">' . $mostrar['Nombre'] . '<br> $' . $mostrar['Valor'] . '</div>';
        $html .= '<div class="nombre-chico">' . $mostrar['Nombre_Almacen'];
        $html .= '</div>';
        $html .= '</div>';
    }
} else {

    $html .= ' <h2>SIN RESULTADOS...</h2>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
