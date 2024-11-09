<?php
session_start();
include 'db.php';

if ($_SESSION['tipo_usuario'] !== 'estudiante' || !isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$id_estudiante = $_SESSION['id'];

// Consultar tutorías disponibles y verificar si el estudiante ya solicitó cada tutoría
$sql = "SELECT 
            tutorias.id AS tutoria_id, 
            tutorias.titulo, 
            tutorias.descripcion, 
            tutorias.fecha_hora, 
            usuarios.nombre AS tutor_nombre,
            (SELECT estado FROM solicitudes WHERE id_tutoria = tutorias.id AND id_estudiante = :id_estudiante) AS solicitud_estado
        FROM tutorias
        JOIN usuarios ON tutorias.id_tutor = usuarios.id
        WHERE tutorias.estado = 'disponible'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
$stmt->execute();
$tutorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Tutorías Disponibles</title>
    <link rel="stylesheet" href="style/estilos_ver_tutorias.css">
</head>
<body>
    <div class="container">
        <h1>Tutorías Disponibles</h1>
        <?php if (count($tutorias) > 0): ?>
            <ul>
                <?php foreach ($tutorias as $tutoria): ?>
                    <li class="tutoria-card">
                        <p><strong>Título:</strong> <?php echo htmlspecialchars($tutoria['titulo']); ?></p>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($tutoria['descripcion']); ?></p>
                        <p><strong>Fecha y Hora:</strong> <?php echo htmlspecialchars($tutoria['fecha_hora']); ?></p>
                        <p><strong>Tutor:</strong> <?php echo htmlspecialchars($tutoria['tutor_nombre']); ?></p>

                        <?php if ($tutoria['solicitud_estado']): ?>
                            <p class="solicitud-enviada">Solicitud enviada: <?php echo htmlspecialchars($tutoria['solicitud_estado']); ?></p>
                        <?php else: ?>
                            <form action="solicitar_tutoria.php" method="POST">
                                <input type="hidden" name="tutoria_id" value="<?php echo $tutoria['tutoria_id']; ?>">
                                <button type="submit">Solicitar Tutoría</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-tutorias">No hay tutorías disponibles en este momento.</p>
        <?php endif; ?>
    </div>

    <script src="js/ver_tutorias.js"></script>
</body>
</html>