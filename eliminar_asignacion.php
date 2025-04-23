<?php
include("config.php");

if (!isset($_GET['asignacion_id'])) {
    header("Location: asignacion_aulas.php?error=1&mensaje=ID de asignación no proporcionado");
    exit;
}

$asignacion_id = intval($_GET['asignacion_id']);

// Eliminar la asignación específica
$query = "DELETE FROM asignaciones WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $asignacion_id);

if ($stmt->execute()) {
    header("Location: asignacion_aulas.php?success=1&mensaje=Asignación eliminada correctamente");
} else {
    header("Location: asignacion_aulas.php?error=1&mensaje=Error al eliminar la asignación");
}

$stmt->close();
exit;
?>
