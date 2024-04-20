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

</style>

<body>
    <?php
    $sql1 = "SELECT * FROM tiendas WHERE ID=$id_almacen;";
    $result1 = mysqli_query($conexion, $sql1);

    while ($mostrar2 = mysqli_fetch_array($result1)) {
    ?>
        <div class="container">
            <div class="image-container">
                <img src="<?php echo $mostrar2['Link_Imagen'] ?>" alt="Sin Imagen">
            </div>
            <div class="data-container">
                <div>
                    <strong>
                        <h1><?php echo $mostrar2['Nombre_Almacen'] ?></h1>
                    </strong>
                </div>
                <div>

                    <h2>Direccion:<?php echo $mostrar2['Direccion'] ?></h2>

                </div>
                <div>

                    <h3>Detalles:<?php echo $mostrar2['Acepta_Tarjetas'] ?> - Checkbox(CajaVecina, Vende Cigarros,etc...)</h3>

                </div>
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
        <div id="pan" class="pan div"></div>

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
                        <td><?php echo $mostrar2['Nombre'] ?></td>
                        <td><?php echo $mostrar2['marca'] ?></td>
                        <td>$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>

        <div class="rating-box div">
            <header>Calificacion del Almacen</header>
            <div class="product-stars-container">
                <div class="stars product-stars">
                    <!-- Estrellas del anime -->
                    <?php
                    /*
                $id_anime = $fila["id"];

                $sql = "SELECT promedio FROM calificaciones WHERE ID_Anime=$id_anime"; // Ajusta el ID según tu estructura de base de datos
                //echo $sql;
                $result = $conexion->query($sql);
                
                // Obtener y almacenar las calificaciones en el array
                if ($result->num_rows > 0) {
                    // Obtener la primera fila (solo debería haber una fila si estás buscando un ID específico)
                    $row = $result->fetch_assoc();

                    $calificacion = $row["promedio"];
                } else {
                    $calificacion = 0;
                }
                */
                    $calificacion = 5;

                    // Establecer el número de estrellas activas según la calificación
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $calificacion) {
                            echo '<i class="fa-solid fa-star active"></i>';
                        } else {
                            echo '<i class="fa-solid fa-star"></i>';
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- Texto de calificación del anime -->
            <div class="rating-text product-rating">Promedio: <span class="product-rating-value"><?php echo $calificacion ?></span></div>
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
                        echo $mostrar2['Indefinido'];
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
                <td><?php echo $mostrar2['Nombre'] ?></td>
                <td><?php echo $mostrar2['marca'] ?></td>
                <td>$<?php echo number_format($mostrar2['Valor'], 0, ',', '.') ?></td>
                <td><?php echo date('Y-m-d', strtotime($mostrar2['Fecha_Registro'])) ?></td>
            </tr>
    <?php
        }
        echo "</table>";
        echo "</div>";
    }
    ?>


    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        var myChart = echarts.init(document.getElementById('pan'));
        //SELECT * FROM `registro_productos` where ID_Almacen="1" AND ID_Producto="3"
        // Specify the configuration items and data for the chart
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