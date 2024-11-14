<?php
session_start();
include 'db.php';

// Verificar que el estudiante esté autenticado
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'estudiante') {
    echo 'error'; // Si no está autenticado, mostrar error
    exit();
}

if (isset($_POST['tutoria_id']) && is_numeric($_POST['tutoria_id'])) {
    $tutoria_id = $_POST['tutoria_id'];
    $id_estudiante = $_SESSION['id'];

    // Verificar si el estudiante ya ha solicitado esta tutoría
    $query = "SELECT * FROM solicitudes WHERE id_tutoria = :tutoria_id AND id_estudiante = :id_estudiante";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':tutoria_id', $tutoria_id, PDO::PARAM_INT);
    $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt->execute();
    $solicitud_existente = $stmt->fetch();

    if ($solicitud_existente) {
        // Si la solicitud ya existe, devolver un error
        echo 'solicitud_existente';
        exit();
    }

    // Insertar la nueva solicitud en la base de datos
    $query = "INSERT INTO solicitudes (id_estudiante, id_tutoria, estado, fecha_solicitud) VALUES (:id_estudiante, :tutoria_id, 'pendiente', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt->bindParam(':tutoria_id', $tutoria_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo 'success'; // Si todo es correcto, devolver 'success'
    } else {
        echo 'error'; // Si algo falla, devolver 'error'
    }
} else {
    echo 'error'; // Si no se recibe el ID de la tutoría
    exit();
}
?>
