<?php
require 'bd.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiendas - Sistema de Gestión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --info-color: #560bad;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            padding-bottom: 60px;
        }

        /* Sidebar/Navbar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            z-index: 1000;
            transform: translateX(0);
            transition: var(--transition);
            box-shadow: var(--shadow);
            padding-top: 20px;
        }

        .sidebar-header {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .menu-items {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            border-left: 4px solid transparent;
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .menu-item span {
            font-weight: 500;
        }

        /* Content area */
        .content-wrapper {
            margin-left: 250px;
            padding: 30px;
            transition: var(--transition);
        }

        .page-title {
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            display: inline-block;
        }

        /* Card styles */
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            border: none;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        .card-body {
            padding: 20px;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-floating {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            margin: 5px;
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        /* Form controls */
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            border-color: var(--primary-color);
        }

        /* Search area */
        .search-area {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .search-form {
            display: flex;
            gap: 15px;
        }

        .search-form .form-control {
            width: 100%;
        }

        /* Products, stores display */
        .product-grid,
        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .product-card,
        .store-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover,
        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image,
        .store-image {
            width: 100%;
            height: 180px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 2em;
        }

        .product-details,
        .store-details {
            padding: 20px;
        }

        .product-title,
        .store-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .product-price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2em;
        }

        .store-address {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .product-count {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9em;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .badge-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .badge-success {
            background-color: var(--success-color);
            color: white;
        }

        /* Select2 customization */
        .select2-container .select2-selection--multiple {
            min-height: 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 5px 10px;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                transform: translateX(0);
            }

            .sidebar-header {
                justify-content: center;
                padding: 15px 0;
            }

            .sidebar-brand,
            .menu-item span {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 15px 0;
            }

            .menu-item i {
                margin-right: 0;
                font-size: 1.2em;
            }

            .content-wrapper {
                margin-left: 70px;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                margin-left: 0;
                padding: 15px;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1100;
                background-color: var(--primary-color);
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: var(--shadow);
            }
        }

        /* Tab content transitions */
        .tab-content {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .tab-content.active {
            display: block;
            opacity: 1;
        }

        /* Modal customizations */
        .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            border-top: none;
            padding: 20px;
        }

        /* Loader */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Mobile menu toggle */
        .mobile-toggle {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a class="sidebar-brand" onclick="showTab(1)">
                <i class="fas fa-store mr-2"></i> TiendApp
            </a>
        </div>

        <ul class="menu-items">
            <li class="menu-item active" onclick="showTab(1)">
                <i class="fas fa-box"></i>
                <span>Productos</span>
            </li>
            <li class="menu-item" onclick="showTab(2)">
                <i class="fas fa-bread-slice"></i>
                <span>Pan</span>
            </li>
            <li class="menu-item" onclick="showTab(3)">
                <i class="fas fa-shop"></i>
                <span>Almacenes</span>
            </li>
            <li class="menu-item" onclick="showTab(4)">
                <i class="fas fa-cart-shopping"></i>
                <span>Supermercados</span>
            </li>
            <li class="menu-item">
                <a href="./login.html" style="color: inherit; text-decoration: none; display: flex; align-items: center;">
                    <i class="fas fa-right-to-bracket"></i>
                    <span>Login</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <!-- Productos Tab -->
        <div id="tab-1" class="tab-content active fade-in">
            <h1 class="page-title animate__animated animate__fadeIn">Lista de Productos</h1>

            <div class="search-area animate__animated animate__fadeIn">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="search" id="campo" class="form-control" placeholder="Buscar productos...">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#ModalNuevo">
                            <i class="fas fa-plus me-2"></i> Nuevo Producto
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="toggleFilter()">
                            <i class="fas fa-filter me-2"></i> Filtrar
                        </button>
                    </div>
                </div>

                <div id="filterContainer" class="mt-3" style="display: none;">
                    <form action="categorias.php" method="POST" id="filtro">
                        <div class="row">
                            <div class="col-md-8">
                                <select name="filtro" id="filtro-select" class="form-select" onchange="this.form.submit()">
                                    <option value="">Todas las categorías</option>
                                    <!-- PHP Categories will go here -->
                                </select>
                            </div>
                            <div class="col-md-4 mt-2 mt-md-0">
                                <button type="submit" class="btn btn-primary w-100">Aplicar Filtro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="content" class="product-grid animate__animated animate__fadeIn">
                <!-- Products will be loaded here dynamically -->
                <div class="loading">
                    <div class="loading-spinner"></div>
                </div>
            </div>
        </div>

        <!-- Pan Tab -->
        <div id="tab-2" class="tab-content fade-in">
            <h1 class="page-title">Pan</h1>

            <div id="content_2" class="store-grid animate__animated animate__fadeIn">
                <!-- Stores will be loaded here dynamically -->
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">24</div>
                        <div class="store-details">
                            <h5 class="store-title">Almacén Don Pedro</h5>
                            <p class="store-address">Av. Principal 123</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Acepta Tarjetas</span>
                                <span class="badge badge-success">Caja Vecina</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">18</div>
                        <div class="store-details">
                            <h5 class="store-title">La Esquina</h5>
                            <p class="store-address">Calle Secundaria 456</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Carga Bip</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">32</div>
                        <div class="store-details">
                            <h5 class="store-title">Minimarket ABC</h5>
                            <p class="store-address">Pasaje Los Robles 789</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Vende Alcohol</span>
                                <span class="badge badge-success">Vende Cigarros</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Almacenes Tab -->
        <div id="tab-3" class="tab-content fade-in">
            <h1 class="page-title animate__animated animate__fadeIn">Lista de Almacenes</h1>

            <div class="search-area animate__animated animate__fadeIn">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalNuevoAlmacen">
                            <i class="fas fa-plus me-2"></i> Nuevo Almacén
                        </button>
                    </div>
                    <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-outline-primary" onclick="toggleStoreFilter()">
                            <i class="fas fa-filter me-2"></i> Filtrar Almacenes
                        </button>
                    </div>
                </div>

                <div id="storeFilterContainer" class="mt-3" style="display: none;">
                    <select class="js-example-basic-multiple form-control" name="filtro_almacen[]" id="filtro_almacen" multiple="multiple">
                        <option value="acepta_tarjetas">Acepta Tarjetas</option>
                        <option value="caja_vecina">Caja Vecina</option>
                        <option value="carga_bip">Carga Bip</option>
                        <option value="vende_cigarros">Vende Cigarros</option>
                        <option value="vende_alcohol">Vende Alcohol</option>
                    </select>
                </div>
            </div>

            <div id="content_3" class="store-grid animate__animated animate__fadeIn">
                <!-- Stores will be loaded here dynamically -->
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">24</div>
                        <div class="store-details">
                            <h5 class="store-title">Almacén Don Pedro</h5>
                            <p class="store-address">Av. Principal 123</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Acepta Tarjetas</span>
                                <span class="badge badge-success">Caja Vecina</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">18</div>
                        <div class="store-details">
                            <h5 class="store-title">La Esquina</h5>
                            <p class="store-address">Calle Secundaria 456</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Carga Bip</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                        <div class="store-image">
                            <i class="fas fa-shop"></i>
                        </div>
                        <div class="product-count">32</div>
                        <div class="store-details">
                            <h5 class="store-title">Minimarket ABC</h5>
                            <p class="store-address">Pasaje Los Robles 789</p>
                            <div class="store-features">
                                <span class="badge badge-primary">Vende Alcohol</span>
                                <span class="badge badge-success">Vende Cigarros</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supermercados Tab -->
        <div id="tab-4" class="tab-content fade-in">
            <h1 class="page-title animate__animated animate__fadeIn">Lista de Supermercados</h1>

            <div class="search-area animate__animated animate__fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalNuevoSupermercado">
                            <i class="fas fa-plus me-2"></i> Nuevo Supermercado
                        </button>
                    </div>
                </div>
            </div>

            <div id="content_4" class="store-grid animate__animated animate__fadeIn">
                <!-- Supermarkets will be loaded here dynamically -->
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn">
                        <div class="store-image">
                            <i class="fas fa-cart-shopping"></i>
                        </div>
                        <div class="product-count">120</div>
                        <div class="store-details">
                            <h5 class="store-title">Super Ahorro</h5>
                            <p class="store-address">Av. Principal 1000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
                        <div class="store-image">
                            <i class="fas fa-cart-shopping"></i>
                        </div>
                        <div class="product-count">95</div>
                        <div class="store-details">
                            <h5 class="store-title">Jumbo</h5>
                            <p class="store-address">Mall Plaza Centro</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card store-card animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                        <div class="store-image">
                            <i class="fas fa-cart-shopping"></i>
                        </div>
                        <div class="product-count">85</div>
                        <div class="store-details">
                            <h5 class="store-title">Líder</h5>
                            <p class="store-address">Av. Las Condes 2500</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevo Supermercado Modal -->
    <div class="modal fade" id="ModalNuevoSupermercado" tabindex="-1" aria-labelledby="modalSupermercadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSupermercadoLabel">Registro de Supermercados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="guardado_datos_almacen.php">
                        <input type="hidden" name="tipo" value="Supermercado">
                        <div class="mb-3">
                            <label for="nombre_supermercado" class="form-label">Nombre del Supermercado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-cart-shopping"></i></span>
                                <input type="text" name="nombre_supermercado" id="nombres_super" list="nombre_sup" class="form-control" required>
                                <datalist id="nombre_sup">
                                    <!-- PHP Supermarket Names will go here -->
                                </datalist>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion_supermercado" class="form-label">Dirección del Supermercado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
                                <input type="text" name="direccion_supermercado" class="form-control">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar Supermercado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Nuevo Producto Modal -->
    <div class="modal fade" id="ModalNuevo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="guardado_datos.php">
                        <div class="mb-3">
                            <label for="nombre_almacen" class="form-label">Nombre de la Tienda</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-store"></i></span>
                                <input type="text" name="nombre_almacen" id="nombre" list="nombres" class="form-control" required>
                                <datalist id="nombres">
                                    <!-- PHP Store Names will go here -->
                                </datalist>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                <input type="text" name="nombre_producto" id="producto" list="productos" class="form-control" required>
                                <datalist id="productos">
                                    <!-- PHP Products will go here -->
                                </datalist>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio del Producto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio" value="0" min="10" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevo Almacen Modal -->
    <div class="modal fade" id="ModalNuevoAlmacen" tabindex="-1" aria-labelledby="modalAlmacenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlmacenLabel">Registro de Almacenes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" method="POST" action="guardado_datos_almacen.php">
                        <input type="hidden" name="tipo" value="Almacen">
                        <div class="mb-3">
                            <label for="nombre_almacen" class="form-label">Nombre del Almacen</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-shop"></i></span>
                                <input type="text" name="nombre_almacen" id="nombres" list="nombre_al" class="form-control" required>
                                <datalist id="nombre_al">
                                    <!-- PHP Store Names will go here -->
                                </datalist>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion_almacen" class="form-label">Dirección del Almacen</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
                                <input type="text" name="direccion_almacen" id="direccion" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Características</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="acepta_tarjetas" id="acepta_tarjetas">
                                        <label class="form-check-label" for="acepta_tarjetas">
                                            Acepta Tarjetas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="caja_vecina" id="caja_vecina">
                                        <label class="form-check-label" for="caja_vecina">
                                            Caja Vecina
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="carga_bip" id="carga_bip">
                                        <label class="form-check-label" for="carga_bip">
                                            Carga Bip
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="vende_cigarros" id="vende_cigarros">
                                        <label class="form-check-label" for="vende_cigarros">
                                            Vende Cigarros
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="vende_alcohol" id="vende_alcohol">
                                        <label class="form-check-label" for="vende_alcohol">
                                            Vende Alcohol
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar Almacen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: 'Selecciona características',
                allowClear: true,
                width: '100%'
            });

        });

        // Tab navigation
        let currentTab = 1;

        function showTab(tabId) {
            if (tabId === currentTab) {
                return; // Already on this tab
            }

            // Update menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });

            document.querySelector(`.menu-item:nth-child(${tabId})`).classList.add('active');

            // Hide current tab
            document.getElementById(`tab-${currentTab}`).classList.remove('active');

            // Show new tab with animation
            const newTab = document.getElementById(`tab-${tabId}`);
            newTab.classList.add('active');

            // Update current tab value
            currentTab = tabId;

            // Close sidebar on mobile when tab is changed
            if (window.innerWidth < 576) {
                document.getElementById('sidebar').classList.remove('show');
            }
        }

        // Toggle filter container
        function toggleFilter() {
            const filterContainer = document.getElementById('filterContainer');
            if (filterContainer.style.display === 'none') {
                filterContainer.style.display = 'block';
                filterContainer.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                filterContainer.style.display = 'none';
            }
        }

        // Toggle store filter container
        function toggleStoreFilter() {
            const storeFilterContainer = document.getElementById('storeFilterContainer');
            if (storeFilterContainer.style.display === 'none') {
                storeFilterContainer.style.display = 'block';
                storeFilterContainer.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                storeFilterContainer.style.display = 'none';
            }
        }

        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside of it (on mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (window.innerWidth < 576 &&
                !sidebar.contains(event.target) &&
                event.target !== sidebarToggle &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Product search functionality
        document.getElementById('campo').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');

            products.forEach(product => {
                const title = product.querySelector('.product-title').textContent.toLowerCase();
                const price = product.querySelector('.product-price').textContent.toLowerCase();
                const store = product.querySelector('.store-address')?.textContent.toLowerCase() || '';

                if (title.includes(searchTerm) || price.includes(searchTerm) || store.includes(searchTerm)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            // You could add AJAX calls here to load products from a database
            // For example:

            $.ajax({
                url: 'cargar_productos.php',
                type: 'GET',
                success: function(response) {
                    document.getElementById('content').innerHTML = response;

                    // Add animation to new elements
                    document.querySelectorAll('.product-card').forEach((card, index) => {
                        card.classList.add('animate__animated', 'animate__fadeIn');
                        card.style.animationDelay = `${index * 0.1}s`;
                    });
                }
            });
            $.ajax({
                url: 'cargar_pan.php',
                type: 'GET',
                success: function(response) {
                    document.getElementById('content_2').innerHTML = response;

                }
            });
            $.ajax({
                url: 'cargar_tiendas.php',
                type: 'GET',
                success: function(response) {
                    document.getElementById('content_3').innerHTML = response;

                    // Add animation to new elements
                    document.querySelectorAll('.product-card').forEach((card, index) => {
                        card.classList.add('animate__animated', 'animate__fadeIn');

                        // Limita el delay a un máximo de 1 segundo
                        const delay = Math.min(index * 0.03, 1);
                        card.style.animationDelay = `${delay}s`;
                    });

                }
            });
            $.ajax({
                url: 'cargar_super.php',
                type: 'GET',
                success: function(response) {
                    document.getElementById('content_4').innerHTML = response;

                    // Add animation to new elements
                    document.querySelectorAll('.product-card').forEach((card, index) => {
                        card.classList.add('animate__animated', 'animate__fadeIn');

                        // Limita el delay a un máximo de 1 segundo
                        const delay = Math.min(index * 0.03, 1);
                        card.style.animationDelay = `${delay}s`;
                    });

                }
            });

            // Initialize any other components
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
        });

        // Form validation for new product form
        document.querySelector('#ModalNuevo form').addEventListener('submit', function(event) {
            const precio = this.querySelector('input[name="precio"]').value;
            if (precio <= 0) {
                alert('El precio debe ser mayor que 0');
                event.preventDefault();
            }
        });

        // Form validation for new store forms
        const storeFormValidation = function(event) {
            const name = this.querySelector('input[name="nombre_almacen"], input[name="nombre_supermercado"]').value;
            if (name.trim() === '') {
                alert('El nombre es obligatorio');
                event.preventDefault();
            }
        };

        document.querySelector('#ModalNuevoAlmacen form').addEventListener('submit', storeFormValidation);
        document.querySelector('#ModalNuevoSupermercado form').addEventListener('submit', storeFormValidation);

        // Handle responsive design
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth >= 576 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    </script>

</body>

</html>