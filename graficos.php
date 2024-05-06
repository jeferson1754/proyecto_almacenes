<!DOCTYPE html>
<html lang="en">
<?php
include 'bd.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="css/tienda.css?v=<?php echo time(); ?>">
    <title>Graficos</title>
</head>
<style>
    body {
        background-color: black;
    }

    #chart-container {
        position: relative;
        height: 50vh;
        overflow: hidden;
    }
</style>

<body>

    <div class="contenedor">
        <?php
        // Consulta SQL para obtener los datos de 'Valor' y 'Fecha_Historial'
        // Definir la consulta SQL con un parámetro placeholder
        $sql = "
    SELECT 
        historial_productos.Valor, 
        historial_productos.Diferencia, 
        historial_productos.Fecha_Historial 
    FROM 
        historial_productos 
    INNER JOIN 
        registro_productos 
    ON 
        registro_productos.ID = historial_productos.ID_Registro 
    WHERE 
        registro_productos.ID_Almacen = ? 
    AND 
        registro_productos.ID_Producto = 3 
    ORDER BY 
        historial_productos.ID ASC
        LIMIT 1
";

        // Declarar el valor del parámetro
        $id_almacen = 3; // Usar el valor entero en lugar de cadena para consistencia

        // Preparar la consulta SQL
        $stmt = $conexion->prepare($sql);

        //echo $sql . "<br>";

        // Verificar que la preparación fue exitosa
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        // Vincular el parámetro al statement
        $stmt->bind_param("i", $id_almacen); // "i" indica que el parámetro es un entero

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado de la ejecución
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
        <div class="div"></div>
        <div class="div"></div>

    </div>


    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        //SELECT * FROM `registro_productos` where ID_Almacen="1" AND ID_Producto="3"
        // Specify the configuration items and data for the chart
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
    </script>

</body>

</html>