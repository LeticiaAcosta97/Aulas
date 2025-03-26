<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipo_id = $_POST['equipo_id'];
    $fecha_mantenimiento = $_POST['fecha_mantenimiento'];
    $observaciones = $_POST['observaciones'];

    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Actualizar última fecha de mantenimiento
        $sql1 = "UPDATE equipos SET ultima_fecha_mantenimiento = ? WHERE id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("si", $fecha_mantenimiento, $equipo_id);
        $stmt1->execute();

        // Insertar en historial
        $sql2 = "INSERT INTO historial_mantenimiento (equipo_id, fecha_mantenimiento, observaciones) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iss", $equipo_id, $fecha_mantenimiento, $observaciones);
        $stmt2->execute();

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>