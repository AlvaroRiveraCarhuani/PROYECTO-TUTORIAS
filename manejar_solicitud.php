<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];

    $nuevo_estado = $accion === 'aceptar' ? 'aceptada' : 'rechazada';

    $query = "UPDATE solicitudes SET estado = :nuevo_estado WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nuevo_estado', $nuevo_estado);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'nuevo_estado' => $nuevo_estado]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
