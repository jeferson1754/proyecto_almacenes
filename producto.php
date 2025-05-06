<?php include 'bd.php';
// Obtener ID del producto por GET
$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Obtener detalles del producto
$productDetails = null;
$sqlDetalle = "SELECT p.ID, p.Nombre FROM productos p WHERE p.ID = $productId";
$resultDetalle = $conexion->query($sqlDetalle);
if ($resultDetalle && $resultDetalle->num_rows > 0) {
    $productDetails = $resultDetalle->fetch_assoc();
}

// Obtener todas las tiendas con el producto y precios
$tiendas = [];
$precios = [];
if ($productDetails) {
    $sqlTiendas = "SELECT t.Nombre_Almacen AS tienda, rp.Valor as Precio, t.Direccion
                  FROM registro_productos rp
                  INNER JOIN tiendas t ON rp.ID_Almacen = t.ID
                  WHERE rp.ID_Producto = $productId
                  ORDER BY rp.Valor ASC";

    $resultTiendas = $conexion->query($sqlTiendas);
    if ($resultTiendas && $resultTiendas->num_rows > 0) {
        while ($row = $resultTiendas->fetch_assoc()) {
            $tiendas[] = $row;
            $precios[] = $row['Precio'];
        }

        $precioMin = min($precios);
        $precioMax = max($precios);
        $ahorro = $precioMax - $precioMin;
        $beneficioPorc = $precioMax > 0 ? round(($ahorro / $precioMax) * 100, 2) : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador de Precios | <?= $productDetails ? htmlspecialchars($productDetails['Nombre']) : 'Producto no encontrado' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #10b981;
            --dark-color: #1f2937;
            --light-color: #f3f4f6;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            padding: 2rem 0;
        }

        .product-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .price-comparison {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .price-badge {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .store-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .store-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .best-price {
            border-left: 5px solid var(--secondary-color);
        }

        .store-logo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            font-weight: bold;
        }

        .price {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .savings-indicator {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: var(--light-color);
        }

        .progress-container {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            border-radius: 5px;
            transition: width 1s ease-in-out;
        }

        .map-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .map-link:hover {
            color: var(--secondary-color);
        }

        .map-link i {
            margin-right: 0.5rem;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .header {
                border-radius: 0;
                padding: 1.5rem 0;
            }

            .price-badge {
                font-size: 1rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.4s;
        }
    </style>
</head>

<body>
    <div class="header text-center py-4">
        <div class="container">
            <h1 class="display-5 fw-bold"><i class="fas fa-search-dollar me-2"></i>Comparador de Precios</h1>
            <p class="lead">Encuentra el mejor precio para tus compras</p>
        </div>
    </div>

    <div class="container pb-5">
        <?php if ($productDetails): ?>
            <!-- Detalles del producto -->
            <div class="product-card card mb-4 fade-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-light rounded p-3">
                                <i class="fas fa-box-open fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="card-title h3 mb-0"><?= htmlspecialchars($productDetails['Nombre']) ?></h2>
                            <p class="text-muted mb-0"><small>ID: <?= $productId ?></small></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($tiendas)): ?>
                <!-- Resumen de precios -->
                <div class="price-comparison fade-in delay-1">
                    <h3 class="h4 mb-4"><i class="fas fa-chart-line me-2"></i>Comparativa de Precios</h3>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="bg-success bg-opacity-10 p-3 rounded text-center h-100">
                                <div class="text-success mb-1"><i class="fas fa-tags fa-lg"></i></div>
                                <span class="d-block text-muted small mb-1">Precio más bajo</span>
                                <span class="price-badge bg-success text-white">$<?= number_format($precioMin, 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-danger bg-opacity-10 p-3 rounded text-center h-100">
                                <div class="text-danger mb-1"><i class="fas fa-arrow-up fa-lg"></i></div>
                                <span class="d-block text-muted small mb-1">Precio más alto</span>
                                <span class="price-badge bg-danger text-white">$<?= number_format($precioMax, 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-center h-100">
                                <div class="text-primary mb-1"><i class="fas fa-piggy-bank fa-lg"></i></div>
                                <span class="d-block text-muted small mb-1">Ahorro potencial</span>
                                <span class="price-badge bg-primary text-white">$<?= number_format($ahorro, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="savings-indicator">
                        <div class="text-center w-100">
                            <p class="mb-1">Puedes ahorrar hasta un <strong class="text-success"><?= $beneficioPorc ?>%</strong> comparando precios</p>
                            <div class="progress-container">
                                <div class="progress-bar" id="savings-bar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Listado de tiendas -->
                <h3 class="h4 mb-3 fade-in delay-2"><i class="fas fa-store me-2"></i>Tiendas disponibles</h3>
                <div class="stores-container">
                    <?php $index = 0;
                    foreach ($tiendas as $tienda): $index++; ?>
                        <div class="store-card card mb-3 fade-in delay-<?= min($index + 2, 4) ?> <?= $tienda['Precio'] == $precioMin ? 'best-price' : '' ?>">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="store-logo">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1"><?= htmlspecialchars($tienda['tienda']) ?></h5>
                                        <?php if (!empty($tienda['Direccion'])): ?>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($tienda['Direccion']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-auto mt-2 mt-md-0">
                                        <div class="d-flex flex-column align-items-md-end">
                                            <div class="price mb-1">$<?= number_format($tienda['Precio'], 0, ',', '.') ?></div>
                                            <?php if ($tienda['Precio'] == $precioMin): ?>
                                                <span class="badge bg-success text-white">Mejor precio</span>
                                            <?php elseif ($tienda['Precio'] == $precioMax): ?>
                                                <span class="badge bg-danger text-white">Precio más alto</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">
                                                    +$<?= number_format($tienda['Precio'] - $precioMin, 0, ',', '.') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($tienda['Direccion'])): ?>
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="https://www.google.com/maps/search/?q=<?= urlencode($tienda['Direccion']) ?>"
                                            class="map-link" target="_blank">
                                            <i class="fas fa-directions"></i> Cómo llegar
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="alert alert-warning mt-3 fade-in delay-1">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No hay precios disponibles para este producto en las tiendas.
                </div>
            <?php endif; ?>

        <?php else: ?>
            <?php if ($productId > 0): ?>
                <div class="alert alert-danger fade-in">
                    <i class="fas fa-times-circle me-2"></i>
                    No se encontró el producto con ID <?= $productId ?>.
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i> Volver a la lista de productos
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-info fade-in">
                    <i class="fas fa-info-circle me-2"></i>
                    Por favor proporciona un ID de producto en la URL (?product_id=ID).
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i> Ver lista de productos
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1">Comparador de Precios &copy; <?= date('Y') ?></p>
            <p class="small text-muted mb-0">Encuentra siempre los mejores precios</p>
        </div>
    </footer>

    <a href="#" class="back-to-top" id="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Animar la barra de progreso de ahorro
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const savingsBar = document.getElementById('savings-bar');
                if (savingsBar) {
                    savingsBar.style.width = '<?= $beneficioPorc ?>%';
                }
            }, 500);

            // Botón volver arriba
            const backToTop = document.getElementById('back-to-top');

            if (backToTop) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.classList.add('active');
                    } else {
                        backToTop.classList.remove('active');
                    }
                });

                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>
</body>

</html>