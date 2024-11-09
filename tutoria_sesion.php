<?php
require 'db.php'; // Conexión a la base de datos

$id_tutoria = $_GET['id']; // ID de la tutoría

// Verificar si el ID de la tutoría es válido
if (!isset($id_tutoria) || !is_numeric($id_tutoria)) {
    die("ID de tutoría no válido.");
}

try {
    $query = "SELECT * FROM contenidos WHERE id_tutoria = :id_tutoria";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id_tutoria' => $id_tutoria]);
    $contenidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los contenidos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Dinámica de Contenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Gestión de Contenido Dinámico</h1>

        <!-- Pestañas dinámicas -->
        <ul class="nav nav-tabs" id="tabMenu" role="tablist">
            <?php foreach ($contenidos as $index => $contenido): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab<?= $index ?>" data-bs-toggle="tab" href="#content<?= $index ?>" role="tab">
                        Sección <?= $index + 1 ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content mt-3" id="tabContent">
            <?php foreach ($contenidos as $index => $contenido): ?>
                <div class="tab-pane fade" id="content<?= $index ?>" role="tabpanel">
                    <p><strong>Tipo:</strong> <span class="tipo"><?= htmlspecialchars($contenido['tipo']) ?></span></p>
                    <p><strong>Contenido:</strong> <span class="contenido"><?= htmlspecialchars($contenido['contenido']) ?></span></p>

                    <button class="btn btn-warning btn-modificar" data-id="<?= $contenido['id'] ?>">Modificar</button>
                    <button class="btn btn-primary btn-guardar-cambios" data-id="<?= $contenido['id'] ?>" style="display:none;">Guardar Cambios</button>
                    <button class="btn btn-danger btn-eliminar" data-id="<?= $contenido['id'] ?>">Eliminar</button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Botón para agregar una nueva sección -->
        <button id="addTab" class="btn btn-success mt-3">Agregar Sección</button>

        <!-- Formulario dinámico para agregar una nueva sección -->
        <div id="newSectionForm" class="mt-4" style="display: none;">
            <h3>Agregar Nueva Sección</h3>
            <form id="formNewSection">
                <div class="mb-3">
                    <label for="newTipo" class="form-label">Tipo</label>
                    <select id="newTipo" class="form-select" required>
                        <option value="diapositiva">Diapositiva</option>
                        <option value="imagen">Imagen</option>
                        <option value="video">Video</option>
                        <option value="link">Link</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="newContenido" class="form-label">Contenido</label>
                    <textarea id="newContenido" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Sección</button>
                <button type="button" class="btn btn-secondary" id="cancelNewSection">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/tutoria_contenido.js" defer></script>
</body>
</html>
