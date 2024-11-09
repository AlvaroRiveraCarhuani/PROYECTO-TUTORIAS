<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar la contraseña
    if ($user && password_verify($password, $user['password'])) {
        // Guardar la información del usuario en la sesión
        $_SESSION['id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

        // Redirigir según el tipo de usuario
        if ($user['tipo_usuario'] === 'tutor') {
            // Redirigir al tutor a mis_tutorias.php
            header("Location: mis_tutorias.php");
            exit();
        } else {
            // Redirigir a los estudiantes a ver_tutorias.php
            header("Location: ver_tutorias.php");
            exit();
        }
    } else {
        // Mostrar mensaje de error si las credenciales son incorrectas
        echo "Credenciales incorrectas.";
    }
}
?>
