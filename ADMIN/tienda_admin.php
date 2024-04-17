<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_GET['id'])) {
    $id_almacen = urldecode($_GET['id']);
    echo "Los datos recibidos son: ID: " . $id_almacen . "";
}
include '../bd.php';
require 'permisos.php';

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
    <link rel="stylesheet" href="../css/tienda.css">
    <title>Document</title>
</head>
<style>

</style>

<body>

    <div class="container">
        <div class="image-container">
            <img src="https://lh5.googleusercontent.com/p/AF1QipMDXxCPhLZltMZs71Ne621XrEw7NL_sJ0L0GNTD=w750-h606-p-k-no" alt="No Data" srcset="">
        </div>
        <div class="data-container">
            <div>
                <strong>
                    <h1>El Palto</h1>
                </strong>
            </div>
            <div>

                <h2>Direccion: Alcalde Luis Osorio 765, 8150000 Puente Alto, Región Metropolitana</h2>

            </div>
            <div>

                <h3>Detalles:Checkbox(CajaVecina, Vende Cigarros,etc...)</h3>

            </div>
        </div>
    </div>
    <div class="contenedor">
        <div id="pan" class="pan div"></div>

        <div class="div">
            <h2>LOS 3 PRODUCTOS MAS VISTOS</h2>
            <table>
                <tr>
                    <td>⭐</td>
                    <td>1</td>
                    <td>1k Pan</td>
                    <td>2000</td>
                </tr>
                <tr>
                    <td></td>
                    <td>2</td>
                    <td>Mantequilla</td>
                    <td>2500</td>

                </tr>
                <tr>
                    <td></td>
                    <td>3</td>
                    <td>Huevos</td>
                    <td>300</td>

                </tr>
            </table>
        </div>
        <div class="div">
            <h2>CALIFICACION DEL ALMACEN</h2>
            <h2 style="font-size: 40px;">⭐⭐⭐⭐⭐</h2>

            <h3>Notas: Atencion muy Amable</h3>
        </div>
    </div>


    <div class="data-container total">
        <table>
            <tr>
                <th>Producto</th>
                <th>Valor</th>
                <th>Promocion</th>
                <th>Beneficio</th>
                <th>Fecha_Termino</th>
            </tr>
            <tr>
                <td>Juan</td>
                <td>30</td>
                <td>30</td>
                <td>Madrid</td>
                <td>Madrid</td>
            </tr>
            <tr>
                <td>María</td>
                <td>25</td>
                <td>30</td>
                <td>Barcelona</td>
                <td>Madrid</td>
            </tr>
            <tr>
                <td>Carlos</td>
                <td>40</td>
                <td>30</td>
                <td>Valencia</td>
                <td>Madrid</td>
            </tr>
        </table>
    </div>
    <div class="data-container total">
        <table>
            <tr>
                <th>Producto</th>
                <th>Marca</th>
                <th>Precio</th>
                <th>Fecha_Registro</th>
            </tr>
            <tr>
                <td>Juan</td>
                <td>30</td>
                <td>Madrid</td>
                <td>Madrid</td>
            </tr>
            <tr>
                <td>María</td>
                <td>25</td>
                <td>Barcelona</td>
                <td>Madrid</td>
            </tr>
            <tr>
                <td>Carlos</td>
                <td>40</td>
                <td>Valencia</td>
                <td>Madrid</td>
            </tr>
        </table>
    </div>


    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        var myChart = echarts.init(document.getElementById('pan'));

        // Specify the configuration items and data for the chart
        var option = {
            xAxis: {
                type: 'category',
                data: ['12-02-2022', '13-04-2024']
            },
            yAxis: {
                type: 'value'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br /> {b}: <span style="color: black;text-align:center">{c}</span> '
            },
            series: [{
                data: [150, 350],
                name: 'Valor del',
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
                subtext: 'Diferencia: {a|+200}',
                subtextStyle: {
                    rich: {
                        a: {
                            color: 'red', // Mismo color que el texto principal
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
    </script>

</body>

</html>