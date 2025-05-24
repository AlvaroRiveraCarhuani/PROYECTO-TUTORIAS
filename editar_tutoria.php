<?php
session_start();
include('db.php');

// Verificar si el usuario está logueado y es un tutor
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

// Verificar si se recibió el ID de la tutoría
if (!isset($_GET['id'])) {
    header('Location: mis_tutorias.php');
    exit();
}

$id_tutoria = $_GET['id'];

// Obtener los datos de la tutoría
$query = "SELECT * FROM tutorias WHERE id = :id AND id_tutor = :id_tutor";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id_tutoria, PDO::PARAM_INT);
$stmt->bindParam(':id_tutor', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$tutoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tutoria) {
    echo "Tutoría no encontrada o no tienes permiso para editarla.";
    exit();
}

// Manejar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_hora = $_POST['fecha_hora'];
    $estado = $_POST['estado'];

    $updateQuery = "UPDATE tutorias SET titulo = :titulo, descripcion = :descripcion, fecha_hora = :fecha_hora, estado = :estado WHERE id = :id AND id_tutor = :id_tutor";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':titulo', $titulo);
    $updateStmt->bindParam(':descripcion', $descripcion);
    $updateStmt->bindParam(':fecha_hora', $fecha_hora);
    $updateStmt->bindParam(':estado', $estado);
    $updateStmt->bindParam(':id', $id_tutoria, PDO::PARAM_INT);
    $updateStmt->bindParam(':id_tutor', $_SESSION['id'], PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        header('Location: mis_tutorias.php?mensaje=actualizado');
        exit();
    } else {
        echo "Error al actualizar la tutoría.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tutoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2 class="text-center mb-4">Editar Tutoría</h2>
        <form method="POST" class="bg-white p-4 rounded shadow">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo htmlspecialchars($tutoria['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" required><?php echo htmlspecialchars($tutoria['descripcion']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                <input type="datetime-local" id="fecha_hora" name="fecha_hora" class="form-control" value="<?php echo htmlspecialchars($tutoria['fecha_hora']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="pendiente" <?php if ($tutoria['estado'] === 'pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="completada" <?php if ($tutoria['estado'] === 'completada') echo 'selected'; ?>>Completada</option>
                </select>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="mis_tutorias.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
