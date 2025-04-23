<?php
include("config.php");

$response = ['success' => false];

if (isset($_POST['equipo_id']) && isset($_POST['estado'])) {
    $equipo_id = intval($_POST['equipo_id']);
    $estado = $_POST['estado'];
    
    // Actualizar en la tabla de equipos
    $query = "UPDATE equipos SET estado = ?, fecha_estado = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $estado, $equipo_id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
    
    $stmt->close();
}

echo json_encode($response);
?>