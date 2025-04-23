<?php
include("config.php");

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

if (!isset($_POST['nro_factura']) || !isset($_POST['patrimonio']) || 
    !isset($_POST['descripcion']) || !isset($_POST['cantidad']) || 
    !isset($_POST['marca']) || !isset($_POST['modelo'])) {
    $response['message'] = 'Faltan datos requeridos';
    echo json_encode($response);
    exit;
}

try {
    $nro_factura = trim($_POST['nro_factura']);
    $patrimonio_base = trim($_POST['patrimonio']);
    $descripcion = trim($_POST['descripcion']);
    $cantidad = intval($_POST['cantidad']);
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);

    if (empty($nro_factura) || empty($patrimonio_base) || empty($descripcion) || 
        $cantidad < 1 || empty($marca) || empty($modelo)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Insertar múltiples equipos con patrimonio correlativo
    for ($i = 0; $i < $cantidad; $i++) {
        $patrimonio_actual = strval(intval($patrimonio_base) + $i);
        
        $query = "INSERT INTO equipos (tipo, nombre, nro_factura, nro_serie, descripcion, marca, modelo, fecha_instalacion, periodo_mantenimiento) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_DATE(), 180)";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $tipo = explode(' ', $descripcion)[0];
        $nombre = $descripcion; // Usando la descripción como nombre
        
        if (!$stmt->bind_param("sssssss", $tipo, $nombre, $nro_factura, $patrimonio_actual, $descripcion, $marca, $modelo)) {
            throw new Exception($stmt->error);
        }
        
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    }

    $response['success'] = true;
    $response['message'] = 'Equipos guardados correctamente';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error al guardar: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
