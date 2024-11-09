<?php
session_start();
include 'db.php';

if ($_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitud_id = $_POST['solicitud_id'];
    $accion = $_POST['accion'];

    // Determinar el nuevo estado según la acción
    $nuevo_estado = ($accion === 'aceptar') ? 'aceptada' : 'rechazada';

    // Actualizar el estado de la solicitud en la base de datos
    $sql = "UPDATE solicitudes SET estado = :nuevo_estado WHERE id = :solicitud_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nuevo_estado', $nuevo_estado);
    $stmt->bindParam(':solicitud_id', $solicitud_id);

    if ($stmt->execute()) {
        echo "Solicitud " . ($accion === 'aceptar' ? "aceptada" : "rechazada") . " exitosamente.";
    } else {
        echo "Error al procesar la solicitud.";
    }
    header("Location: dashboard_tutor.php");
    exit();
}
?>


