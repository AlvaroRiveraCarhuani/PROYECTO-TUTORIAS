<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $contenido = $_POST['contenido'];

    if (!empty($id) && !empty($tipo) && !empty($contenido)) {
        try {
            $query = "UPDATE contenidos SET tipo = :tipo, contenido = :contenido WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':tipo' => $tipo,
                ':contenido' => $contenido,
                ':id' => $id
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
}
?>
