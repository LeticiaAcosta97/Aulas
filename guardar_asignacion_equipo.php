<?php
include("config.php");

header('Content-Type: application/json');

if (!isset($_POST['equipo_id']) || !isset($_POST['aula_id']) || !isset($_POST['cantidad'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$equipo_id = intval($_POST['equipo_id']);
$aula_id = intval($_POST['aula_id']);
$cantidad = intval($_POST['cantidad']);

try {
    $stmt = $conn->prepare("INSERT INTO aulas_equipos (aula_id, equipo_id, cantidad) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $aula_id, $equipo_id, $cantidad);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Error al guardar la asignación");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}