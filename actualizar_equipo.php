<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['equipo_id'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $nro_serie = $_POST['nro_serie'];
    $fecha_instalacion = $_POST['fecha_instalacion'];
    $periodo_mantenimiento = $_POST['periodo_mantenimiento'];

    $sql = "UPDATE equipos SET 
            tipo = ?, 
            marca = ?, 
            modelo = ?, 
            nro_serie = ?,
            fecha_instalacion = ?,
            periodo_mantenimiento = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $tipo, $marca, $modelo, $nro_serie, $fecha_instalacion, $periodo_mantenimiento, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}