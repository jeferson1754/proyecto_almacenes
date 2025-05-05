<?php
require 'bd.php'; // Asegúrate de incluir tu archivo de conexión


try {
    $sql = "SELECT rp.ID, Nombre_Almacen, Nombre, Valor, (a.ID) as ID_Almacen 
            FROM registro_productos rp 
            INNER JOIN tiendas a ON rp.ID_Almacen = a.ID 
            INNER JOIN productos p ON rp.ID_Producto = p.ID 
            WHERE p.ID = 3 
            ORDER BY `rp`.`Valor` ASC";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }

    $animationDelay = 0;
    $output = '';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $output .= '
            <div class="col-md-12 mb-4">
                <div class="card product-card animate__animated animate__fadeIn">
                    <div class="product-image">
                        <i class="fas fa-bread-slice"></i>
                    </div>
                    <div class="product-details">
                        <h5 class="product-title">' . htmlspecialchars($row['Nombre']) . '</h5>
                        <p class="product-price">$' . number_format($row['Valor'], 0, ',', '.') . '</p>
                        <p class="store-address">' . htmlspecialchars($row['Nombre_Almacen']) . '</p>
                        <a href="tienda.php?id=' . $row['ID_Almacen'] . '" class="btn btn-sm btn-primary mt-2">
                            Ver Almacen
                        </a>
                    </div>
                </div>
            </div>';
        }
    } else {
        $output = '
        <div class="col-12 text-center py-4">
            <i class="fas fa-bread-slice fa-3x mb-3 text-muted"></i>
            <h5 class="text-muted">No se encontraron productos de pan</h5>
        </div>';
    }

    echo $output;
} catch (Exception $e) {
    echo '
    <div class="col-12 text-center py-4">
        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
        <h5 class="text-danger">Error al cargar los productos</h5>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
    </div>';
}

mysqli_close($conexion);
