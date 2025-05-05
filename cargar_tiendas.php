<?php
require 'bd.php';

try {
    $filtros = isset($_POST['filtros']) ? $_POST['filtros'] : [];

    $sql = "SELECT t.*, COUNT(rp.ID) AS total_productos 
            FROM tiendas t 
            LEFT JOIN registro_productos rp ON t.ID = rp.ID_Almacen 
            WHERE t.Tipo = 'Almacen'";

    if (!empty($filtros)) {
        $condiciones = [];
        foreach ($filtros as $filtro) {
            $condiciones[] = "t.Detalles LIKE '%" . mysqli_real_escape_string($conexion, $filtro) . "%'";
        }
        if (count($condiciones) > 0) {
            $sql .= " AND " . implode(" AND ", $condiciones);
        }
    }

    $sql .= " GROUP BY t.ID ORDER BY total_productos DESC";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }

    $output = '';

    if (mysqli_num_rows($result) > 0) {
        while ($mostrar2 = mysqli_fetch_array($result)) {
            $variable_id = $mostrar2["ID"];
            $direccion = $mostrar2['Direccion'] ?? "Sin Direccion";

            $output .= '
                 <div class="col-md-12 mb-4">
                        <a href="tienda.php?id=' . $variable_id . '" style="text-decoration:none">
                            <div class="card product-card animate__animated animate__fadeIn">
                                <div class="store-image">
                                        <i class="fas fa-shop"></i>
                                    </div>
                                        <div class="product-count">' . $mostrar2['total_productos'] . '</div>
                                
                                    <div class="store-details">
                                        <h5 class="store-title">' . htmlspecialchars($mostrar2['Nombre_Almacen']) . '</h5>
                                        <p class="store-address">' . htmlspecialchars($direccion) . '</p>
                                        <div class="store-features">
                                            <span class="badge badge-primary">Acepta Tarjetas</span>
                                            <span class="badge badge-success">Caja Vecina</span>
                                        </div>
                                    </div>
                     
                                </div>
                        </a>
                </div>';
        }
    } else {
        $output = '
        <div class="col-12 text-center py-4">
            <i class="fas fa-store-slash fa-3x mb-3 text-muted"></i>
            <h5 class="text-muted">No se encontraron almacenes</h5>
        </div>';
    }

    echo $output;
} catch (Exception $e) {
    echo '
    <div class="col-12 text-center py-4">
        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
        <h5 class="text-danger">Error al cargar los almacenes</h5>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
    </div>';
}

mysqli_close($conexion);
