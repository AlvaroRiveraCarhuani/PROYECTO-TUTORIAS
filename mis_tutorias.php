<?php
session_start();
include('db.php');

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header('Location: login.html');
    exit();
}

$id_tutor = $_SESSION['id'];

// Consultar las tutorías creadas por el tutor usando PDO
$query = "SELECT * FROM tutorias WHERE id_tutor = :id_tutor";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
$stmt->execute();
$tutorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tutorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style/tutorias.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Tutorías</h2>
            <button id="theme-toggle" class="btn btn-outline-secondary">
                <i id="theme-icon" class="bi bi-moon"></i>
            </button>
        </div>

        <div class="mb-3">
            <input type="text" id="buscarTutoria" class="form-control" placeholder="Buscar tutoría por título...">
        </div>

        <div class="text-end mb-3">
            <a href="crear_tutoria.php" class="btn btn-primary">Crear Nueva Tutoría</a>
        </div>

        <?php if (count($tutorias) > 0): ?>
            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Fecha y Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                            <th>Solicitudes</th>
                        </tr>
                    </thead>
                    <tbody id="tutoriasBody">
                        <?php foreach ($tutorias as $index => $tutoria): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($tutoria['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($tutoria['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($tutoria['fecha_hora']); ?></td>
                                <td><?php echo htmlspecialchars($tutoria['estado']); ?></td>
                                <td>
                                    <a href="editar_tutoria.php?id=<?php echo $tutoria['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?php echo $tutoria['id']; ?>)">Eliminar</button>
                                    <a href="tutoria_sesion.php?id=<?php echo $tutoria['id']; ?>" class="btn btn-success btn-sm">Entrar</a>
                                </td>
                                <td>
                                    <a href="ver_solicitudes.php?id=<?php echo $tutoria['id']; ?>" class="btn btn-info btn-sm">Ver Solicitudes</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <p>No tienes tutorías creadas.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta tutoría?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button typem="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/tema.js" defer></script>
    <script src="js/buscarTutoria.js" defer></script>
    <script src="js/eliminarTutoria.js" defer></script>
</body>
</html>