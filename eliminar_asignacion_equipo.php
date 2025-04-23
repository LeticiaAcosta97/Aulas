<?php
include("config.php");

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo_id = isset($_POST['equipo_id']) ? intval($_POST['equipo_id']) : 0;
    $aula_id = isset($_POST['aula_id']) ? intval($_POST['aula_id']) : 0;

    if ($equipo_id && $aula_id) {
        $query = "DELETE FROM aulas_equipos WHERE equipo_id = ? AND aula_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $equipo_id, $aula_id);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Equipo eliminado correctamente';
        } else {
            $response['message'] = 'Error al eliminar el equipo';
        }
    } else {
        $response['message'] = 'Datos incompletos';
    }
}

header('Content-Type: application/json');
echo json_encode($response);