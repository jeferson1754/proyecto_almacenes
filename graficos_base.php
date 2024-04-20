<!DOCTYPE html>
<html lang="en">
<?php
include 'bd.php';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grafico</title>
</head>
<style>
    #chart-container {
        position: relative;
        height: 350px;
    }
</style>

<body>
    <?php

    // Obtener el valor de id_almacen
    $id_almacen = 6;

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
    <div id="chart-container"></div>
    <script src="https://fastly.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
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