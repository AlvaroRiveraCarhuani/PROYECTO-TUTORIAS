<?php
session_start();
if ($_SESSION['tipo_usuario'] !== 'estudiante') {
    header("Location: login.html");
    exit();
}

// Redirigir a la página de ver tutorías
header("Location: ver_tutorias.php");
exit();
?>
