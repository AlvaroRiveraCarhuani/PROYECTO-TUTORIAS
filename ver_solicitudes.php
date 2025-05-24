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
                            <tr id="solicitud-<?php echo $solicitud['id']; ?>">
                                <td><?php echo htmlspecialchars($solicitud['nombre_estudiante']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td id="estado-<?php echo $solicitud['id']; ?>"><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm aceptar-solicitud" data-id="<?php echo $solicitud['id']; ?>">Aceptar</button>
                                    <button class="btn btn-danger btn-sm rechazar-solicitud" data-id="<?php echo $solicitud['id']; ?>">Rechazar</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.aceptar-solicitud').forEach(button => {
                button.addEventListener('click', () => manejarSolicitud(button.dataset.id, 'aceptar'));
            });

            document.querySelectorAll('.rechazar-solicitud').forEach(button => {
                button.addEventListener('click', () => manejarSolicitud(button.dataset.id, 'rechazar'));
            });

            function manejarSolicitud(id, accion) {
                fetch(`manejar_solicitud.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&accion=${accion}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`estado-${id}`).textContent = data.nuevo_estado;
                    } else {
                        alert('Error al procesar la solicitud.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
</body>
</html>
