<?php
include("config.php");

if (!isset($_GET['id'])) {
    header("Location: cursos.php?error=1&mensaje=ID del curso no proporcionado");
    exit;
}

$curso_id = intval($_GET['id']);

// Verificar si el curso está asignado a algún aula
$check_query = "SELECT COUNT(*) as total FROM asignaciones WHERE curso_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['total'] > 0) {
    header("Location: cursos.php?error=1&mensaje=No se puede eliminar el curso porque está asignado a un aula");
    exit;
}

// Eliminar el curso
$delete_query = "DELETE FROM cursos WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $curso_id);

if ($stmt->execute()) {
    header("Location: cursos.php?success=1&mensaje=Curso eliminado correctamente");
} else {
    header("Location: cursos.php?error=1&mensaje=Error al eliminar el curso");
}

$stmt->close();
exit;
?>