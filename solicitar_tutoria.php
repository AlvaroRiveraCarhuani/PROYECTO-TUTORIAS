<?php
session_start();
include 'db.php';

if ($_SESSION['tipo_usuario'] !== 'estudiante' || !isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_SESSION['id'];
    $id_tutoria = $_POST['tutoria_id'];

    // Insertar solicitud en la base de datos
    $sql = "INSERT INTO solicitudes (id_estudiante, id_tutoria, fecha_solicitud, estado) 
            VALUES (:id_estudiante, :id_tutoria, NOW(), 'pendiente')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt->bindParam(':id_tutoria', $id_tutoria, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ver_tutorias.php?success=1");
    } else {
        header("Location: ver_tutorias.php?error=1");
    }
    exit();
}