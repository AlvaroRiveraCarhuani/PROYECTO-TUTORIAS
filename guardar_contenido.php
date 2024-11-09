<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tutoria = $_POST['id_tutoria'];
    $tipo = $_POST['tipo'];
    $contenido = $_POST['contenido'];

    if (!empty($id_tutoria) && !empty($tipo) && !empty($contenido)) {
        try {
            $query = "INSERT INTO contenidos (id_tutoria, tipo, contenido) VALUES (:id_tutoria, :tipo, :contenido)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':id_tutoria' => $id_tutoria,
                ':tipo' => $tipo,
                ':contenido' => $contenido
            ]);
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
}
?>
