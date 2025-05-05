<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_GET['id'])) {
    $id_almacen = urldecode($_GET['id']);
}
include 'bd.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <!-- ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/star.css?v=<?php echo time(); ?>">
    <title>Datos Almacén</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        .store-header {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }

        .store-header:hover {
            transform: translateY(-5px);
        }

        .store-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .store-info {
            padding: 20px;
        }

        .store-name {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .store-address {
            color: var(--secondary-color);
            font-size: 1.2rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .store-address i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .store-rating {
            font-size: 2rem;
            color: #f1c40f;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .store-details {
            background-color: rgba(52, 152, 219, 0.05);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .store-details h3 {
            color: var(--secondary-color);
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .store-details p {
            margin-bottom: 5px;
            color: #666;
            position: relative;
            padding-left: 20px;
        }

        .store-details p:before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
        }

        .info-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 25px;
            height: 100%;
            transition: all 0.3s;
        }

        .info-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .info-card h2 {
            color: var(--secondary-color);
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .chart-container {
            height: 400px;
            width: 100%;
        }

        /* Tablas */
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table td,
        .custom-table th {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .price {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .section-title {
            text-align: center;
            margin: 40px 0 30px;
            position: relative;
            font-size: 2rem;
            color: var(--secondary-color);
            font-weight: 700;
        }

        .section-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
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

        .animated {
            animation: fadeIn 0.8s ease-out forwards;
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

        /* Badges */
        .badge-promotion {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
            margin-left: 10px;
        }

        .badge-date {
            background-color: var(--secondary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .store-image {
                height: 200px;
            }

            .store-name {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <?php
        $sql = "SELECT t.*, ct.Promedio 
            FROM tiendas t 
            LEFT JOIN calificaciones_tiendas ct ON t.ID = ct.ID_Tienda 
            WHERE t.ID = $id_almacen;";
        $result = mysqli_query($conexion, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <!-- Header con información de la tienda -->
            <div class="store-header animated delay-1">
                <img src="<?php echo $row['Link_Imagen'] ?>" alt="<?php echo $row['Nombre_Almacen'] ?>" class="store-image">
                <div class="store-info row">
                    <div class="col-md-8">
                        <h1 class="store-name"><?php echo $row['Nombre_Almacen'] ?></h1>
                        <h2 class="store-address">
                            <i class="fas fa-map-marker-alt"></i> <?php echo $row['Direccion'] ?>
                        </h2>

                        <div class="store-details">
                            <h3><i class="fas fa-info-circle"></i> Detalles</h3>
                            <?php
                            $detalles = $row['Detalles'];
                            $detallesArray = explode(',', $detalles);
                            foreach ($detallesArray as $detalle) {
                                $detalle = trim($detalle);
                                echo "<p>$detalle</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <?php if (!empty($row['Promedio'])) { ?>
                            <div class="store-rating">
                                <span><?php echo $row['Promedio'] ?></span>
                                <i class="fas fa-star ms-2"></i>
                            </div>
                            <div class="text-muted">Calificación promedio</div>
                        <?php } else { ?>
                            <div class="store-rating text-muted">
                                <i class="far fa-star me-2"></i>
                                <span>Sin calificaciones</span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="row">
            <!-- Gráfico de precios -->
            <div class="col-lg-8 mb-4 animated delay-2">
                <div class="info-card">
                    <h2><i class="fas fa-chart-line me-2"></i>Evolución de precios</h2>
                    <div id="chart-container" class="chart-container"></div>
                </div>
            </div>

            <!-- Últimos 3 productos -->
            <div class="col-lg-4 mb-4 animated delay-2">
                <div class="info-card">
                    <h2><i class="fas fa-shopping-basket me-2"></i>Últimos 3 productos</h2>
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.Valor FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 3;";
                            $result1 = mysqli_query($conexion, $sql1);

                            while ($mostrar2 = mysqli_fetch_array($result1)) {
                            ?>
                                <tr>
                                    <td><?php echo $mostrar2['Nombre'];
                                        if ($mostrar2['marca'] != "") {
                                            echo " - " . $mostrar2['marca'];
                                        }
                                        ?></td>
                                    <td class="price">$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        // Consulta para Promociones
        $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.Valor,registro_promocion.* FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID INNER JOIN registro_promocion ON registro_promocion.ID_Registro=registro_productos.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 3;";

        $result1 = mysqli_query($conexion, $sql1);

        if ($result1->num_rows > 0) {
        ?>
            <h2 class="section-title animated delay-2"><i class="fas fa-percentage me-2"></i>Promociones</h2>
            <div class="row animated delay-3">
                <div class="col-12">
                    <div class="info-card">
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Marca</th>
                                        <th>Precio</th>
                                        <th>Promoción</th>
                                        <th>Vigencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($mostrar2 = mysqli_fetch_array($result1)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $mostrar2['Nombre'] ?></td>
                                            <td><?php echo $mostrar2['marca'] ?></td>
                                            <td class="price">$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                                            <td><span class="badge-promotion"><?php echo $mostrar2['Promocion'] ?></span></td>
                                            <td>
                                                <?php
                                                if ($mostrar2['Indefinido'] != "SI") {
                                                    echo '<span class="badge-date">' . $mostrar2['Fecha_Termino'] . '</span>';
                                                } else {
                                                    echo '<span class="badge-date">Indefinido</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        // Consulta para Últimos Productos
        $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.* FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 10 OFFSET 3;";
        $result1 = mysqli_query($conexion, $sql1);

        if ($result1->num_rows > 0) {
        ?>
            <h2 class="section-title animated delay-3"><i class="fas fa-clipboard-list me-2"></i>Últimos Productos</h2>
            <div class="row animated delay-3">
                <div class="col-12">
                    <div class="info-card">
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($mostrar2 = mysqli_fetch_array($result1)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $mostrar2['Nombre'];
                                                if ($mostrar2['marca'] != "") {
                                                    echo " - " . $mostrar2['marca'];
                                                }
                                                ?></td>
                                            <td class="price">$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                                            <td>
                                                <i class="far fa-calendar me-2"></i>
                                                <?php echo date('d-m-Y', strtotime($mostrar2['Fecha_Registro'])); ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        // Consulta SQL para obtener los datos de 'Valor' y 'Fecha_Historial'
        $sql = "SELECT historial_productos.Valor, historial_productos.Diferencia, historial_productos.Fecha_Historial 
        FROM historial_productos 
        INNER JOIN registro_productos ON registro_productos.ID = historial_productos.ID_Registro 
        WHERE registro_productos.ID_Almacen = ? AND registro_productos.ID_Producto = 3 
        ORDER BY historial_productos.ID ASC";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_almacen);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $output = [];
        $date = [];
        $diferencia_con_signo = '0';
        $color = "black";

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $output[] = $row['Valor'];
                $date[] = date('Y-m-d', strtotime($row['Fecha_Historial']));
                $diferencia = $row['Diferencia'];
            }

            // Construir la cadena de datos para 'Valor'
            $output_data = implode(",", array_map(function ($value) {
                return "'" . $value . "'";
            }, $output));
            $output = "data: [" . $output_data . "]";

            // Construir la cadena de datos para 'Fecha_Historial'
            $date_data = implode(",", array_map(function ($value) {
                return "'" . $value . "'";
            }, $date));
            $date = "data: [" . $date_data . "]";

            // Procesamiento adicional para la diferencia
            if ($diferencia > 0) {
                $diferencia_con_signo = '+' . $diferencia;
                $color = "#dc3545"; // Bootstrap danger color
            } elseif ($diferencia < 0) {
                $diferencia_con_signo = '-' . abs($diferencia);
                $color = "#28a745"; // Bootstrap success color
            }
            $texto = "Diferencia: {a|" . $diferencia_con_signo . "}";
        } else {
            $output = "data: [0]";
            $date = "data: [0]";
            $texto = "Sin Datos Aún";
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        var dom = document.getElementById('chart-container');
        var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });

        // Responsive chart
        window.addEventListener('resize', function() {
            myChart.resize();
        });

        var option = {
            grid: {
                left: '5%',
                right: '5%',
                bottom: '10%',
                top: '25%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                <?php echo $date ?>,
                axisLabel: {
                    rotate: 45,
                    interval: 'auto'
                },
                axisLine: {
                    lineStyle: {
                        color: '#3498db'
                    }
                }
            },
            yAxis: {
                type: 'value',
                axisLine: {
                    lineStyle: {
                        color: '#3498db'
                    }
                },
                splitLine: {
                    lineStyle: {
                        type: 'dashed',
                        color: '#eee'
                    }
                }
            },
            tooltip: {
                trigger: 'axis',
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                borderColor: '#3498db',
                borderWidth: 1,
                textStyle: {
                    color: '#333'
                },
                formatter: function(params) {
                    var param = params[0];
                    return `<div style="font-weight:bold;margin-bottom:5px;">Fecha: ${param.name}</div>
                            <div style="display:flex;align-items:center;">
                                <span style="display:inline-block;margin-right:5px;width:10px;height:10px;background-color:${param.color};border-radius:50%;"></span>
                                <span>${param.seriesName}: $${param.value}</span>
                            </div>`;
                }
            },
            series: [{
                <?php echo $output ?>,
                name: 'Valor del Pan',
                type: 'line',
                symbolSize: 8,
                lineStyle: {
                    width: 3,
                    color: '#3498db'
                },
                itemStyle: {
                    color: '#3498db'
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                areaStyle: {
                    color: {
                        type: 'linear',
                        x: 0,
                        y: 0,
                        x2: 0,
                        y2: 1,
                        colorStops: [{
                            offset: 0,
                            color: 'rgba(52, 152, 219, 0.5)'
                        }, {
                            offset: 1,
                            color: 'rgba(52, 152, 219, 0.05)'
                        }]
                    }
                },
                markPoint: {
                    data: [{
                            type: 'max',
                            name: 'Máximo'
                        },
                        {
                            type: 'min',
                            name: 'Mínimo'
                        }
                    ]
                },
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Promedio'
                    }]
                }
            }],
            title: {
                text: 'Precio del Kilo de Pan',
                subtext: '<?php echo $texto ?>',
                subtextStyle: {
                    rich: {
                        a: {
                            color: '<?php echo $color ?>',
                            fontSize: 16,
                            fontWeight: 'bold'
                        }
                    },
                    color: 'black'
                },
                left: 'center',
                z: 100,
                textStyle: {
                    fontSize: 18,
                    fontWeight: 'bold',
                    color: '#2c3e50'
                }
            }
        };

        myChart.setOption(option);

        // Animación al hacer scroll
        const animatedElements = document.querySelectorAll('.animated');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.forEach(el => {
            el.style.opacity = "0";
            observer.observe(el);
        });
    </script>
</body>

</html>