<?php
session_start();
include('db.php');

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tutoria = intval($_POST['id_tutoria']);
    $tipo = $_POST['tipo'];
    $contenido = $_POST['contenido'];

    // Validar datos
    if ($id_tutoria > 0 && !empty($tipo) && !empty($contenido)) {
        $query = "INSERT INTO contenidos (id_tutoria, tipo, contenido) VALUES (:id_tutoria, :tipo, :contenido)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':id_tutoria' => $id_tutoria,
            ':tipo' => $tipo,
            ':contenido' => $contenido
        ]);
        header("Location: tutoria_sesion.php?id=" . $id_tutoria);
        exit();
    } else {
        echo "Faltan datos o son inválidos.";
    }
} else {
    echo "Método no permitido.";
}
