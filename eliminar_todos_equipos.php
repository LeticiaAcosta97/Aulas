<?php
include("config.php");

header('Content-Type: application/json');

try {
    // Start transaction
    $conn->begin_transaction();

    // Delete all maintenance records
    if (!$conn->query("DELETE FROM mantenimientos")) {
        throw new Exception("Error deleting maintenance records");
    }
    
    // Reset equipment assignments
    if (!$conn->query("UPDATE equipos SET aula_id = NULL")) {
        throw new Exception("Error resetting assignments");
    }
    
    // Delete all equipment
    if (!$conn->query("DELETE FROM equipos")) {
        throw new Exception("Error deleting equipment");
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>