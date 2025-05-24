<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $tipo_usuario = $_POST['tipo_usuario'];

    $sql = "INSERT INTO usuarios (nombre, email, password, tipo_usuario) VALUES (:nombre, :email, :password, :tipo_usuario)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario);

    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error en el registro";
    }
}
?>
