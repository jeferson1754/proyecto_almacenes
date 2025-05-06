<?php
include '../bd.php';
require 'permisos.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Tiendas</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 Integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --transition-speed: 0.3s;
            --border-radius: 10px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            transition: all var(--transition-speed) ease;
            overflow-x: hidden;
        }

        /* Navbar y Sidebar */
        .dashboard-navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            transition: all var(--transition-speed) ease;
            overflow-y: auto;
            padding-top: 80px;
        }

        .sidebar-collapsed {
            width: 70px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            border-radius: 8px;
            margin: 8px 12px;
            transition: all var(--transition-speed) ease;
        }

        .sidebar-nav .nav-link {
            padding: 12px 15px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
            border-radius: var(--border-radius);
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        .sidebar-nav .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 25px;
            text-align: center;
        }

        .sidebar-nav .nav-link.active i {
            color: var(--primary-color);
        }

        .sidebar-nav .nav-link span {
            opacity: 1;
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar-collapsed .sidebar-nav .nav-link span {
            opacity: 0;
            display: none;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            padding: 90px 25px 25px;
            transition: all var(--transition-speed) ease;
        }

        .main-content-expanded {
            margin-left: 70px;
        }

        /* Tarjetas */
        .product-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .card-body {
            padding: 20px;
        }

        /* Botones y acciones */
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-speed) ease;
            margin-right: 5px;
            cursor: pointer;
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn.edit {
            color: var(--primary-color);
        }

        .action-btn.edit:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }

        .action-btn.view {
            color: var(--success-color);
        }

        .action-btn.view:hover {
            background-color: rgba(76, 201, 240, 0.1);
        }

        .action-btn.delete {
            color: var(--danger-color);
        }

        .action-btn.delete:hover {
            background-color: rgba(230, 57, 70, 0.1);
        }

        /* DataTables Personalización */
        .dataTables_wrapper {
            margin-top: 20px;
        }

        .dataTables_filter input {
            border-radius: var(--border-radius);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 8px 12px;
            margin-left: 10px;
        }

        .dataTables_length select {
            border-radius: var(--border-radius);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 8px 12px;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-link {
            color: var(--primary-color);
        }

        table.dataTable {
            border-collapse: collapse !important;
            margin-top: 20px;
        }

        table.dataTable thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            color: var(--dark-color);
            font-weight: 600;
        }

        table.dataTable tbody tr {
            background-color: white;
            transition: all var(--transition-speed) ease;
        }

        table.dataTable tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        table.dataTable tbody td {
            vertical-align: middle;
            padding: 15px 10px;
        }

        /* Diseño Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content-expanded {
                margin-left: 0;
            }
        }

        /* Badge personalizado */
        .badge {
            padding: 8px 12px;
            font-weight: 500;
            border-radius: 6px;
        }

        .badge-price {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }

        /* Loader y animaciones */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(67, 97, 238, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Modal estilizado */
        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Tarjeta de vista rápida */
        .quick-view-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .quick-view-card .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
        }

        .quick-view-card .body {
            padding: 20px;
        }

        .quick-view-card .footer {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Toasts/Alertas */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            overflow: hidden;
        }

        .toast-header {
            background-color: var(--primary-color);
            color: white;
        }

        /* Tooltip personalizado */
        .custom-tooltip {
            position: relative;
            display: inline-block;
        }

        .custom-tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: var(--dark-color);
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .custom-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Modo oscuro (opcional) */
        .dark-mode {
            background-color: #121212;
            color: #f5f5f5;
        }

        .dark-mode .sidebar,
        .dark-mode .product-card,
        .dark-mode .modal-content,
        .dark-mode .card-header {
            background-color: #1e1e1e;
            color: #f5f5f5;
        }

        .dark-mode .sidebar-nav .nav-link {
            color: #f5f5f5;
        }

        .dark-mode .sidebar-nav .nav-link:hover,
        .dark-mode .sidebar-nav .nav-link.active {
            background-color: rgba(67, 97, 238, 0.2);
        }

        .dark-mode table.dataTable thead th {
            background-color: #1e1e1e;
            color: #f5f5f5;
        }

        .dark-mode table.dataTable tbody tr {
            background-color: #2d2d2d;
            color: #f5f5f5;
        }

        .dark-mode table.dataTable tbody tr:hover {
            background-color: #333333;
        }
    </style>
</head>

<body>
    <!-- Loader animado -->
    <div class="loading">
        <div class="spinner"></div>
    </div>

    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark dashboard-navbar fixed-top">
        <div class="container-fluid">
            <button class="btn btn-link text-light d-lg-none me-2" id="menu-toggle">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-store me-2"></i>
                Sistema de Gestión de Tiendas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="refresh-data">
                            <i class="fas fa-sync-alt me-1"></i>
                            Actualizar datos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            Administrador
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="toggle-dark-mode">
                            <i class="fas fa-moon"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="../index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-box"></i>
                    <span>Productos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-store"></i>
                    <span>Tiendas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Reportes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content" id="main-content">
        <div class="container-fluid">
            <!-- Header con título y botones de acción -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Gestión de Productos</h1>
                    <p class="text-muted">Administra los productos de todas las tiendas</p>
                </div>
                <div>
                    <button class="btn btn-primary animate__animated animate__fadeIn" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Producto
                    </button>
                </div>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <?php

                    // Obtener total de productos registrados
                    $sql = "SELECT COUNT(*) AS total FROM registro_productos";
                    $result = mysqli_query($conexion, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $total_productos = $row['total'];
                    ?>

                    <div class="product-card p-3 animate__animated animate__fadeIn">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Productos</h6>
                                <h3><?php echo $total_productos; ?></h3>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: rgba(67, 97, 238, 0.1)">
                                <i class="fas fa-box fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card p-3 animate__animated animate__fadeIn" style="animation-delay: 0.1s">
                        <?php
                        // Consulta para contar las tiendas
                        $sql = "SELECT COUNT(*) AS total_tiendas FROM tiendas WHERE Tipo = 'Almacen'";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $total_tiendas = $row['total_tiendas'];

                        // Puedes ajustar este número según lo que consideres como "máximo" para la barra
                        $maximo_tiendas = 10;
                        $porcentaje = min(round(($total_tiendas / $maximo_tiendas) * 100), 100);
                        ?>
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Tiendas</h6>
                                <h3 id="total-stores"><?php echo $total_tiendas; ?></h3>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: rgba(76, 201, 240, 0.1)">
                                <i class="fas fa-store fa-lg text-info"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-info" role="progressbar"
                                style="width: <?php echo $porcentaje; ?>%;"
                                aria-valuenow="<?php echo $porcentaje; ?>"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card p-3 animate__animated animate__fadeIn" style="animation-delay: 0.2s">
                        <?php
                        // Obtener el valor promedio de todos los productos registrados
                        $sql = "SELECT AVG(Valor) AS promedio FROM registro_productos";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $valor_promedio = $row['promedio'] ?? 0;

                        // Formatear con separador de miles
                        $valor_formateado = '$' . number_format($valor_promedio, 0, ',', '.');

                        // Puedes ajustar el valor máximo estimado para calcular el porcentaje (por ejemplo, 100,000)
                        $valor_maximo = 100000;
                        $porcentaje = min(round(($valor_promedio / $valor_maximo) * 100), 100);
                        ?>

                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Valor Promedio</h6>
                                <h3 id="avg-price"><?php echo $valor_formateado; ?></h3>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: rgba(255, 158, 0, 0.1)">
                                <i class="fas fa-tag fa-lg text-warning"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: <?php echo $porcentaje; ?>%;"
                                aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card p-3 animate__animated animate__fadeIn" style="animation-delay: 0.3s">
                        <?php
                        // Obtener la fecha del último registro en la tabla `registro_productos`
                        $sql = "SELECT MAX(Fecha_Registro) AS ultima_fecha FROM registro_productos";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $ultima_fecha = $row['ultima_fecha'] ?? null;

                        $fecha_formateada = 'Sin datos';
                        if ($ultima_fecha) {
                            $hoy = date('Y-m-d');
                            $fecha_solo = date('Y-m-d', strtotime($ultima_fecha));
                            if ($fecha_solo === $hoy) {
                                $fecha_formateada = 'Hoy';
                            } else {
                                $fecha_formateada = date('d/m/Y', strtotime($ultima_fecha));
                            }
                        }

                        // Puedes ajustar el porcentaje manualmente o calcularlo basado en lógica de actualizaciones recientes
                        $porcentaje = ($fecha_solo === $hoy) ? 100 : 90;
                        ?>

                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Último Registro</h6>
                                <h3 id="last-update"><?php echo $fecha_formateada; ?></h3>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: rgba(230, 57, 70, 0.1)">
                                <i class="fas fa-calendar-alt fa-lg text-danger"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: <?php echo $porcentaje; ?>%;"
                                aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Filtros rápidos -->
            <div class="row mb-4">
                <form method="GET" action="index.php">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="product-card animate__animated animate__fadeIn">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <label for="filter-store" class="form-label">Filtrar por Tienda</label>
                                            <select class="form-select" name="store" id="filter-store">
                                                <option value="">Todas las tiendas</option>
                                                <?php
                                                // Conexión a la base de datos
                                                $storeSelected = $_GET['store'] ?? '';

                                                $query = "SELECT ID, Nombre_Almacen FROM tiendas ORDER BY Nombre_Almacen ASC";
                                                $result = $conexion->query($query);

                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($storeSelected == $row['ID']) ? 'selected' : '';
                                                    echo "<option value=\"{$row['ID']}\" $selected>{$row['Nombre_Almacen']}</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <label for="filter-brand" class="form-label">Filtrar por Marca</label>
                                            <select class="form-select" name="brand" id="filter-brand">
                                                <option value="">Todas las marcas</option>
                                                <?php
                                                // Suponiendo que ya tienes la conexión a la base de datos en $conexion
                                                $result = $conexion->query("SELECT ID, Nombre FROM marca WHERE Nombre !='' GROUP BY Nombre ORDER BY Nombre ASC;");
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($_GET['brand'] ?? '') == $row['ID'] ? 'selected' : '';
                                                    echo "<option value=\"{$row['ID']}\" {$selected}>{$row['Nombre']}</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <label for="filter-price" class="form-label">Rango de Precio</label>
                                            <select class="form-select" name="price" id="filter-price">
                                                <option value="">Todos los precios</option>
                                                <option value="1" <?= ($_GET['price'] ?? '') == 1 ? 'selected' : '' ?>>$0 - $10.000</option>
                                                <option value="2" <?= ($_GET['price'] ?? '') == 2 ? 'selected' : '' ?>>$10.000 - $50.000</option>
                                                <option value="3" <?= ($_GET['price'] ?? '') == 3 ? 'selected' : '' ?>>$50.000+</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            <!-- Tabla de productos -->
            <div class="row">
                <div class="col-12">
                    <div class="product-card animate__animated animate__fadeIn">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="products-table" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Marca</th>
                                            <th>Valor</th>
                                            <th>Almacén</th>
                                            <th>Fecha Registro</th>
                                            <th style="text-align: center;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $where = [];

                                        if (!empty($_GET['store'])) {
                                            $store = intval($_GET['store']);
                                            $where[] = "a.ID = $store";
                                        }

                                        if (!empty($_GET['brand'])) {
                                            $brand = intval($_GET['brand']);
                                            $where[] = "m.ID = $brand";
                                        }

                                        if (!empty($_GET['price'])) {
                                            $price = intval($_GET['price']);
                                            if ($price == 1) {
                                                $where[] = "rp.Valor BETWEEN 0 AND 10000";
                                            } elseif ($price == 2) {
                                                $where[] = "rp.Valor BETWEEN 10000 AND 50000";
                                            } elseif ($price == 3) {
                                                $where[] = "rp.Valor > 50000";
                                            }
                                        }

                                        $filterSql = '';
                                        if (!empty($where)) {
                                            $filterSql = 'WHERE ' . implode(' AND ', $where);
                                        }

                                        $sql1 = "SELECT rp.ID, a.Nombre_Almacen, p.Nombre, m.Nombre AS Marca, rp.Valor, a.ID AS ID_Almacen, rp.Fecha_Registro 
                                        FROM registro_productos rp 
                                        INNER JOIN tiendas a ON rp.ID_Almacen = a.ID 
                                        INNER JOIN productos p ON rp.ID_Producto = p.ID 
                                        INNER JOIN marca m ON p.ID_Marca = m.ID 
                                        $filterSql 
                                        ORDER BY rp.ID DESC 
                                        LIMIT 100";
                                        $result1 = mysqli_query($conexion, $sql1);

                                        //echo $sql1;

                                        while ($mostrar2 = mysqli_fetch_array($result1)) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-3" style="width: 40px; height: 40px; background-color: rgba(67, 97, 238, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-box text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?php echo $mostrar2['Nombre'] ?></h6>
                                                            <small class="text-muted">ID: <?php echo $mostrar2['ID'] ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $mostrar2['Marca'] ?></td>
                                                <td>
                                                    <span class="badge badge-price">$<?php echo $mostrar2['Valor'] ?></span>
                                                </td>
                                                <td>
                                                    <a href="tienda_admin.php?id=<?php echo $mostrar2["ID_Almacen"]; ?>" class="text-decoration-none">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-2" style="width: 30px; height: 30px; background-color: rgba(76, 201, 240, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-store text-info"></i>
                                                            </div>
                                                            <span><?php echo $mostrar2['Nombre_Almacen'] ?></span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="far fa-calendar-alt me-2 text-muted"></i>
                                                        <?php echo $mostrar2['Fecha_Registro'] ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#ModalEditarProducto<?php echo $mostrar2['ID']; ?>" data-bs-toggle="tooltip" title="Editar">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </button>

                                                        <a href="historial_producto.php?variable=<?php echo urlencode($mostrar2['ID']); ?>" class="action-btn view" data-bs-toggle="tooltip" title="Ver historial">
                                                            <i class="fa fa-bar-chart"></i>
                                                        </a>

                                                        <button class="action-btn delete" data-bs-toggle="modal" data-bs-target="#Delete<?php echo $mostrar2['ID']; ?>" data-bs-toggle="tooltip" title="Eliminar">
                                                            <i class="fa fa-trash"></i>
                                                        </button>

                                                        <button class="action-btn" style="color: var(--accent-color);" data-bs-toggle="tooltip" title="Vista rápida" onclick="quickView(<?php echo $mostrar2['ID']; ?>)">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar nuevo producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-product-form">
                        <div class="mb-3">
                            <label for="product-name" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="product-name" placeholder="Ingrese el nombre del producto">
                        </div>
                        <div class="mb-3">
                            <label for="product-brand" class="form-label">Marca</label>
                            <select class="form-select" id="product-brand">
                                <option selected disabled>Seleccione una marca</option>
                                <!-- PHP para cargar marcas dinámicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="product-price" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="product-price" placeholder="Ingrese el valor">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="product-store" class="form-label">Almacén</label>
                            <select class="form-select" id="product-store">
                                <option selected disabled>Seleccione un almacén</option>
                                <!-- PHP para cargar almacenes dinámicamente -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="save-product">
                        <i class="fas fa-save me-2"></i>Guardar Producto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para vista rápida -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Vista Rápida del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="quick-view-content">
                        <!-- El contenido se cargará dinámicamente -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary edit-from-quick-view">Editar Producto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de notificaciones -->
    <div class="toast-container"></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Variables
        let isDarkMode = false;
        let isSidebarCollapsed = false;

        // Función para mostrar notificaciones
        function showNotification(title, message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const iconClass = type === 'success' ? 'fas fa-check-circle text-success' :
                type === 'error' ? 'fas fa-exclamation-circle text-danger' :
                type === 'warning' ? 'fas fa-exclamation-triangle text-warning' :
                'fas fa-info-circle text-info';

            const toast = `
                <div class="toast show animate__animated animate__fadeInRight" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="${iconClass} me-2"></i>
                        <strong class="me-auto">${title}</strong>
                        <small>Ahora</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;

            $('.toast-container').append(toast);

            // Auto eliminar la notificación después de 5 segundos
            setTimeout(() => {
                $(`#${toastId}`).removeClass('animate__fadeInRight').addClass('animate__fadeOutRight');
                setTimeout(() => {
                    $(`#${toastId}`).remove();
                }, 500);
            }, 5000);
        }

        // Function para vista rápida de productos
        function quickView(productId) {
            // Abrir modal
            $('#quickViewModal').modal('show');

            // Simular carga de datos (reemplazar con AJAX real)
            setTimeout(() => {
                const data = {
                    id: productId,
                    name: 'Producto Ejemplo',
                    brand: 'Marca Test',
                    price: '$25,000',
                    store: 'Almacén Central',
                    date: '2023-05-05',
                    description: 'Descripción detallada del producto. Esta es una vista rápida que muestra toda la información relevante sin necesidad de ir a otra página.'
                };

                const content = `
                    <div class="quick-view-card">
                        <div class="header">
                            <h5 class="mb-1">${data.name}</h5>
                            <small>ID: ${data.id}</small>
                        </div>
                        <div class="body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Marca</small>
                                    <span>${data.brand}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Precio</small>
                                    <span class="badge badge-price">${data.price}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Almacén</small>
                                    <span>${data.store}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Fecha Registro</small>
                                    <span>${data.date}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted d-block">Descripción</small>
                                    <p>${data.description}</p>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="d-flex justify-content-between">
                                <span>Última actualización: <span class="text-muted">Hoy</span></span>
                                <a href="historial_producto.php?variable=${data.id}" class="text-decoration-none">
                                    <i class="fa fa-bar-chart me-1"></i> Ver historial
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                $('.quick-view-content').html(content);

                // Configurar botón de edición
                $('.edit-from-quick-view').attr('data-product-id', productId);
            }, 800);
        }

        // Toggles
        function toggleDarkMode() {
            isDarkMode = !isDarkMode;
            $('body').toggleClass('dark-mode', isDarkMode);
            $('#toggle-dark-mode i').toggleClass('fa-moon fa-sun');
            localStorage.setItem('darkMode', isDarkMode);
        }

        function toggleSidebar() {
            isSidebarCollapsed = !isSidebarCollapsed;
            $('#sidebar').toggleClass('sidebar-collapsed', isSidebarCollapsed);
            $('#main-content').toggleClass('main-content-expanded', isSidebarCollapsed);
            localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
        }

        // Document Ready
        $(document).ready(function() {
            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Inicializar DataTables
            $('#products-table').DataTable({
                "order": [],
                responsive: true,
                language: {
                    processing: "Tratamiento en curso...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ productos",
                    info: "Mostrando productos del _START_ al _END_ de un total de _TOTAL_ productos",
                    infoEmpty: "No existen registros",
                    infoFiltered: "(filtrado de _MAX_ productos en total)",
                    infoPostFix: "",
                    loadingRecords: "Cargando elementos...",
                    zeroRecords: "No se encontraron los datos de tu búsqueda..",
                    emptyTable: "No hay ningún registro en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Active para ordenar en modo ascendente",
                        sortDescending: ": Active para ordenar en modo descendente"
                    }
                }
            });

            // Eventos de click
            $('#toggle-dark-mode').click(function(e) {
                e.preventDefault();
                toggleDarkMode();
            });

            $('#menu-toggle, #sidebar-toggle').click(function(e) {
                e.preventDefault();
                toggleSidebar();
            });

            // Aplicar filtros (simulado)
            $('#apply-filters').click(function() {
                showNotification('Filtros aplicados', 'Los productos han sido filtrados según tus criterios.', 'info');
            });

            // Botón de refrescar datos
            $('#refresh-data').click(function(e) {
                e.preventDefault();

                // Mostrar spinner
                const originalContent = $(this).html();
                $(this).html('<i class="fas fa-spinner fa-spin me-1"></i> Actualizando...');

                // Simular carga
                setTimeout(() => {
                    $(this).html(originalContent);
                    showNotification('Datos actualizados', 'La lista de productos ha sido actualizada correctamente.');
                }, 1500);
            });

            // Simular guardar producto
            $('#save-product').click(function() {
                // Validación básica
                if ($('#product-name').val() === '') {
                    showNotification('Error de validación', 'Por favor ingresa el nombre del producto.', 'error');
                    return;
                }

                // Simular guardado
                $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
                setTimeout(() => {
                    $('#addProductModal').modal('hide');
                    $(this).html('<i class="fas fa-save me-2"></i>Guardar Producto');
                    showNotification('Producto guardado', 'El producto ha sido guardado exitosamente.');

                    // Resetear formulario
                    $('#add-product-form')[0].reset();
                }, 1500);
            });

            // Editar desde vista rápida
            $('.edit-from-quick-view').click(function() {
                const productId = $(this).attr('data-product-id');
                $('#quickViewModal').modal('hide');

                // Simular apertura de modal de edición
                setTimeout(() => {
                    $(`button[data-bs-target="#ModalEditarProducto${productId}"]`).click();
                }, 500);
            });

            // Cargar preferencias guardadas
            if (localStorage.getItem('darkMode') === 'true') {
                toggleDarkMode();
            }

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                toggleSidebar();
            }

            // Eliminar pantalla de carga
            setTimeout(() => {
                $('.loading').fadeOut();
            }, 800);
        });
    </script>
</body>

</html>