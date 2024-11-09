<?php
session_start();

// Verificar que el usuario es un tutor
if ($_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.html");
    exit();
}

// Verificar que se ha pasado un ID de solicitud válido
if (!isset($_GET['id'])) {
    echo "Solicitud no encontrada.";
    exit();
}

// Conectar a la base de datos
$dsn = 'mysql:host=localhost;dbname=proyecto_tutorias;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar el estado de la solicitud a "aceptada"
    $stmt = $pdo->prepare("UPDATE solicitudes SET estado = 'aceptada' WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);

    echo "Solicitud aceptada con éxito.";
    header("Location: dashboard_tutor.php"); // Redirige de vuelta al dashboard del tutor
    exit();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
