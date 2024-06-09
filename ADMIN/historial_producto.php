<!DOCTYPE html>
<html>

<head>
    <title>Historial</title>
    <!-- Incluir la librería Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://kit.fontawesome.com/8846655159.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/barra.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php
    date_default_timezone_set('America/Santiago');
    $fecha_hora_actual = date('H:i:s');
    require '../bd.php'; // Incluir el archivo de conexión a la base de datos

    if (isset($_GET['variable'])) {
        $variable = urldecode($_GET['variable']);
        //echo "La variable recibida es: " . $variable;
    }

    //Consulta para sacar el titulo y demas
    $consulta1 = "SELECT rp.ID, a.Nombre_Almacen, p.Nombre, m.Nombre AS Marca, rp.Valor, a.ID AS ID_Almacen, rp.Fecha_Registro FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID INNER JOIN marca m ON p.ID_Marca = m.ID WHERE rp.ID='$variable'";
    $resultado1 = mysqli_query($conexion, $consulta1);
    //echo $consulta1;

    while ($fila1 = mysqli_fetch_assoc($resultado1)) {
        $producto = $fila1['Nombre'];
        $almacen = $fila1['Nombre_Almacen'];
    }
    ?>
    <h1 style="font-family:Segoe UI;font-weight: 600;"> <?php echo $producto . " - " . $almacen; ?></h1>

    <!-- Crear un lienzo para el gráfico -->
    <div class="grafico">
        <div id="chart-container" style="height: 300px; width:800px"></div>

    </div>

    <div style="width:50%; margin: 0 auto;">
        <table id="example">
            <thead>
                <tr>
                    <th style="text-align: center;">Valor</th>
                    <th style="text-align: center;">Diferencia</th>
                    <th style="text-align: center;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Consulta para sacar historial de precios
                $sql1 = "SELECT hp.ID, hp.Valor, hp.Diferencia, hp.Fecha_Historial FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID INNER JOIN historial_productos hp ON rp.ID = hp.ID_Registro WHERE rp.ID ='$variable' ORDER BY hp.ID DESC;";

                $result = mysqli_query($conexion, $sql1);
                //echo $sql1;

                while ($mostrar = mysqli_fetch_array($result)) {
                    $id = $mostrar['ID'];
                ?>
                    <tr>
                        <td class="normal"><?php echo $mostrar['Valor'] ?></td>
                        <td class="normal"><?php echo $mostrar['Diferencia'] ?></td>
                        <td class="normal"><?php echo $mostrar['Fecha_Historial'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://fastly.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <?php
    // Consulta SQL para obtener los datos de 'Valor' y 'Fecha_Historial'
    $sql = "SELECT hp.ID, hp.Valor, hp.Diferencia, hp.Fecha_Historial FROM registro_productos rp INNER JOIN tiendas a ON rp.ID_Almacen = a.ID INNER JOIN productos p ON rp.ID_Producto = p.ID INNER JOIN historial_productos hp ON rp.ID = hp.ID_Registro WHERE rp.ID = ? ORDER BY hp.ID ASC;";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $variable);
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

    //echo $diferencia . "<br>" . $diferencia_con_signo . "<br>" . $date . "<br>" . $output . "<br>" . $texto;
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
                name: 'Precio',
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
                text: 'Precio Historico',
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