<?php
session_start();
include('db.php');

// Verificar si el usuario está logueado y es un tutor
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header('Location: login.php'); // Redirigir al login si no está autenticado como tutor
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_tutoria = $_GET['id'];

    // Consultar las solicitudes para la tutoría seleccionada
    $query = "
        SELECT solicitudes.id, usuarios.nombre AS nombre_estudiante, solicitudes.fecha_solicitud, solicitudes.estado
        FROM solicitudes
        JOIN usuarios ON solicitudes.id_estudiante = usuarios.id
        WHERE solicitudes.id_tutoria = :id_tutoria
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_tutoria', $id_tutoria, PDO::PARAM_INT);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Tutoría no válida.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Tutoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <!-- Botón de Atrás -->
        <div class="mb-3">
            <a href="mis_tutorias.php" class="btn btn-secondary">&larr; Atrás</a>
        </div>

        <h2 class="text-center mb-4">Solicitudes de Tutoría</h2>

        <?php if (count($solicitudes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Estudiante</th>
                            <th>Fecha de Solicitud</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solicitud['nombre_estudiante']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                                <td>
                                    <!-- Enlaces para aceptar o rechazar la solicitud -->
                                    <a href="aceptar_solicitud.php?id=<?php echo $solicitud['id']; ?>" class="btn btn-success btn-sm">Aceptar</a>
                                    <a href="rechazar_solicitud.php?id=<?php echo $solicitud['id']; ?>" class="btn btn-danger btn-sm">Rechazar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <p>No hay solicitudes para esta tutoría.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
