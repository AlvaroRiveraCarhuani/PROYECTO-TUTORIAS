<?php
session_start();
include('db.php');

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header('Location: mis_tutorias.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_tutoria = $_GET['id'];

    $query = "SELECT * FROM tutorias WHERE id = :id_tutoria AND id_tutor = :id_tutor";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_tutoria', $id_tutoria, PDO::PARAM_INT);
    $stmt->bindParam(':id_tutor', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $conn->beginTransaction();

        $deleteSolicitudes = $conn->prepare("DELETE FROM solicitudes WHERE id_tutoria = :id_tutoria");
        $deleteSolicitudes->bindParam(':id_tutoria', $id_tutoria, PDO::PARAM_INT);
        $deleteSolicitudes->execute();

        $deleteTutoria = $conn->prepare("DELETE FROM tutorias WHERE id = :id_tutoria");
        $deleteTutoria->bindParam(':id_tutoria', $id_tutoria, PDO::PARAM_INT);
        $deleteTutoria->execute();

        $conn->commit();

        header('Location: mis_tutorias.php');
        exit();
    } else {
        echo "No tienes permiso para eliminar esta tutoría.";
    }
} else {
    echo "ID de tutoría no válido.";
}
