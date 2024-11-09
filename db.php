<?php
$host = 'localhost';
$dbname = 'proyecto_tutorias';
$username = 'root'; // Cambia si tienes otro usuario configurado
$password = ''; // Cambia si tienes contraseña en tu servidor MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
