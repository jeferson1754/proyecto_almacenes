<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_GET['id'])) {
    $id_almacen = urldecode($_GET['id']);
    //echo "Los datos recibidos son: ID: " . $id_almacen . "";
}
include 'bd.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="css/tienda.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/star.css?v=<?php echo time(); ?>">
    <title>Datos Almacen</title>
</head>
<style>
    #chart-container {
        position: relative;
        height: 50vh;
        overflow: hidden;
    }
</style>

<body>
    <?php
    $sql = "SELECT t.*, ct.Promedio 
        FROM tiendas t 
        LEFT JOIN calificaciones_tiendas ct ON t.ID = ct.ID_Tienda 
        WHERE t.ID = $id_almacen;";
    $result = mysqli_query($conexion, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <div class="container">
            <div class="image-container">
                <img src="<?php echo $row['Link_Imagen'] ?>" alt="Sin Imagen">
            </div>
            <div class="data-container">
                <div>
                    <strong>
                        <h1><?php echo $row['Nombre_Almacen'] ?></h1>
                    </strong>
                </div>
                <div>
                    <h2>Dirección: <?php echo $row['Direccion'] ?></h2>
                </div>
                <div>
                    <h3>Detalles:<br></h3>
                    <?php
                    $detalles = $row['Detalles'];
                    $detallesArray = explode(',', $detalles);
                    foreach ($detallesArray as $detalle) {
                        $detalle = trim($detalle);
                        echo "<p> $detalle</p>";
                    }
                    ?>
                </div>
                <?php if (!empty($row['Promedio'])) { ?>
                    <h1><?php echo $row['Promedio'] ?>⭐</h1>
                <?php } ?>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="contenedor">
        <?php
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
                $color = "red";
            } elseif ($diferencia < 0) {
                $diferencia_con_signo = '-' . abs($diferencia);
                $color = "green";
            }
            $texto = "Diferencia: {a|" . $diferencia_con_signo . "}";
        } else {
            $output = "data: [0]";
            $date = "data: [0]";
            $texto = "Sin Datos Aun";
        }
        ?>
        <div class="div">
            <div id="chart-container"></div>
        </div>
        <div class="div">
            <h2>LOS ULTIMOS 3 PRODUCTOS</h2>
            <table>
                <?php
                $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.Valor FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 3;";
                //echo $sql1;
                $result1 = mysqli_query($conexion, $sql1);

                while ($mostrar2 = mysqli_fetch_array($result1)) {
                ?>
                    <tr>
                        <td><?php echo $mostrar2['Nombre'];
                            if ($mostrar2['marca'] != "") {
                                echo " - " . $mostrar2['marca'];
                            }
                            ?></td>
                        <td>$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>

    </div>




    <?php

    $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.Valor,registro_promocion.* FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID INNER JOIN registro_promocion ON registro_promocion.ID_Registro=registro_productos.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 3;";

    $result1 = mysqli_query($conexion, $sql1);

    if ($result1->num_rows > 0) {
        echo '<h2 style="text-align:center">Promociones</h2>';
        echo '<div class="data-container  total">';
        echo "<table";
        while ($mostrar2 = mysqli_fetch_array($result1)) {
    ?>
            <tr>
                <td><?php echo $mostrar2['Nombre'] ?></td>
                <td><?php echo $mostrar2['marca'] ?></td>
                <td>$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                <td><?php echo $mostrar2['Promocion'] ?></td>
                <td><?php
                    if ($mostrar2['Indefinido'] != "SI") {
                        echo $mostrar2['Fecha_Termino'];
                    } else {
                        echo "Indefinido";
                    } ?>
                </td>
            </tr>
    <?php
        }
        echo "</table>";
        echo "</div>";
    }
    ?>


    <?php

    $sql1 = "SELECT productos.Nombre,(marca.Nombre) as marca,registro_productos.* FROM `registro_productos` INNER JOIN productos ON registro_productos.ID_Producto=productos.ID INNER JOIN marca ON productos.ID_Marca=marca.ID where ID_Almacen=$id_almacen ORDER BY registro_productos.ID DESC LIMIT 10 OFFSET 3;";
    $result1 = mysqli_query($conexion, $sql1);

    if ($result1->num_rows > 0) {
        echo ' <h2 style="text-align:center">Ultimos Productos</h2>';
        echo '<div class="data-container total">';
        echo "<table";
        while ($mostrar2 = mysqli_fetch_array($result1)) {
    ?>
            <tr>
                <td><?php echo $mostrar2['Nombre'];
                    if ($mostrar2['marca'] != "") {
                        echo " - " . $mostrar2['marca'];
                    }
                    ?></td>
                <td>$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                <td>
                    <?php
                    // Formatear la fecha en el formato deseado
                    $fechaFormateada = date('d-m-Y', strtotime($mostrar2['Fecha_Registro']));
                    echo $fechaFormateada;
                    ?>
                </td>

            </tr>
    <?php
        }
        echo "</table>";
        echo "</div>";
    }
    ?>


    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        var dom = document.getElementById('chart-container');
        var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        option = {
            xAxis: {
                type: 'category',
                <?php echo $date ?>,
            },
            yAxis: {
                type: 'value'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br /> {b}: <span style="color: black;text-align:center">{c}</span> '
            },
            series: [{
                <?php echo $output ?>,
                name: 'Valor del Pan',
                type: 'line',
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
            }],
            title: {
                text: 'Precio del Kilo de Pan',
                subtext: ' <?php echo $texto ?>',
                subtextStyle: {
                    rich: {
                        a: {
                            color: ' <?php echo $color ?>', // Mismo color que el texto principal
                            fontSize: 16,
                            fontWeight: 'bold'
                        }

                    },
                    color: 'black'
                },
                left: 'center',
                z: 100
            }
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', myChart.resize);

        // Display the chart using the configuration items and data just specified.
        myChart.setOption(option);
        // Función para establecer la calificación según el número
        function setRating(num, stars, ratingText) {
            const starsList = document.querySelectorAll(stars);
            const ratingElement = document.querySelector(ratingText);

            starsList.forEach((star, index) => {
                if (index < num) {
                    star.classList.add("active");
                } else {
                    star.classList.remove("active");
                }
            });

            ratingElement.textContent = `${num}`;
        }
    </script>

</body>

</html>