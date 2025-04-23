<?php
include("config.php");

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    exit;
}

$id = intval($_POST['id']);

try {
    $conn->begin_transaction();

    // Eliminar registros de aulas_equipos
    $stmt = $conn->prepare("DELETE FROM aulas_equipos WHERE equipo_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Eliminar registros de historial_mantenimiento
    $stmt = $conn->prepare("DELETE FROM historial_mantenimiento WHERE equipo_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Finalmente eliminar el equipo
    $stmt = $conn->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $conn->commit();
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Error al eliminar el equipo");
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>