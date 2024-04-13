<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir archivo de conexión a la base de datos
    include 'bd.php';

    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE username = '$username'";
    //echo $sql."<br>";
    $result = mysqli_query($conexion, $sql);

    // Verificar si se encontró un usuario con el nombre de usuario proporcionado
    if (mysqli_num_rows($result) == 1) {
        // Obtener los datos del usuario de la base de datos
        $row = mysqli_fetch_assoc($result);

        // Verificar la contraseña
        if (($password == $row['password'])) {
            // Iniciar sesión y redirigir al usuario a la página de inicio correspondiente a su rol
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $row['rol'];

            if ($row['rol'] == 'admin') {
                header("location: ./ADMIN/");
            } else {
                header("location: index.php");
            }
        } else {
            // Contraseña incorrecta
            echo "Nombre de usuario o contraseña incorrectos. 2";
        }
    } else {
        // Usuario no encontrado
        echo "Nombre de usuario o contraseña incorrectos. 1";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
}
