<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'admin') {
    // Si no tiene sesión iniciada o no es administrador, redirigirlo a la página de inicio de sesión
    header("location: ../login.html");
    unset($_SESSION['username']);
    session_destroy();
    exit(); // Terminar el script para evitar que el resto del código se ejecute
}

//echo $_SESSION['username'] . "<br>" . $_SESSION['rol'];
