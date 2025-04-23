<?php
include("config.php");

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $equipo_id = $_POST['equipo_id'];
        $tipo = $_POST['tipo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $nro_serie = $_POST['nro_serie'];
        $fecha_instalacion = $_POST['fecha_instalacion'];
        $periodo_mantenimiento = $_POST['periodo_mantenimiento'];

        $query = "UPDATE equipos SET 
                  tipo = ?,
                  marca = ?,
                  modelo = ?,
                  nro_serie = ?,
                  fecha_instalacion = ?,
                  periodo_mantenimiento = ?
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssii", $tipo, $marca, $modelo, $nro_serie, $fecha_instalacion, $periodo_mantenimiento, $equipo_id);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Equipo actualizado correctamente';
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        $response['message'] = 'Error al actualizar: ' . $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);