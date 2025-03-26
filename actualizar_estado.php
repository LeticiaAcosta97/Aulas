<?php
include("config.php");

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['equipo_id']) && isset($data['estado'])) {
    $stmt = $conn->prepare("UPDATE equipos SET estado = ?, fecha_estado = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("si", $data['estado'], $data['equipo_id']);
    
    $response = ['success' => $stmt->execute()];
    $stmt->close();
} else {
    $response = ['success' => false];
}

header('Content-Type: application/json');
echo json_encode($response);