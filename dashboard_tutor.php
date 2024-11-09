<?php
session_start();

// Verificar que el usuario es un tutor
if ($_SESSION['tipo_usuario'] !== 'tutor') {
    header("Location: login.html");
    exit();
}

// Conectar a la base de datos
$dsn = 'mysql:host=localhost;dbname=proyecto_tutorias;charset=utf8mb4';
$user = 'root'; // Cambia esto si tienes otra configuración en tu servidor local
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener las solicitudes pendientes para las tutorías del tutor
    $stmt = $pdo->prepare("
        SELECT solicitudes.id, tutorias.titulo, usuarios.nombre AS nombre_estudiante, solicitudes.fecha_solicitud
        FROM solicitudes
        JOIN tutorias ON solicitudes.id_tutoria = tutorias.id
        JOIN usuarios ON solicitudes.id_estudiante = usuarios.id
        WHERE tutorias.id_tutor = :id_tutor AND solicitudes.estado = 'pendiente'
    ");
    $stmt->execute(['id_tutor' => $_SESSION['id']]);
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Tutor</title>
    <link rel="stylesheet" href="style/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido, Tutor <?php echo $_SESSION['nombre']; ?></h1>
        <p>Aquí puedes gestionar tus tutorías y ver solicitudes de estudiantes.</p>

        <?php if (count($solicitudes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tutoría</th>
                        <th>Estudiante</th>
                        <th>Fecha de Solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($solicitud['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($solicitud['nombre_estudiante']); ?></td>
                            <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                            <td>
                                <a href="aceptar_solicitud.php?id=<?php echo $solicitud['id']; ?>" class="action-btn">Aceptar</a>
                                <a href="rechazar_solicitud.php?id=<?php echo $solicitud['id']; ?>" class="action-btn reject">Rechazar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes solicitudes pendientes en este momento.</p>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; <?php echo date("Y"); ?> Sistema de Tutorías</p>
        </div>
    </div>
</body>
</html>
