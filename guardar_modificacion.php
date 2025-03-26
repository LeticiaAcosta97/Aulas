<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y decodificar el JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['equipos']) || !isset($data['aula_id'])) {
        die("Error: Datos incompletos");
    }

    $aula_id = intval($data['aula_id']);

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        foreach ($data['equipos'] as $equipo) {
            $id = isset($equipo['id']) ? intval($equipo['id']) : 0;
            
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE equipos SET descripcion = ?, cantidad = ?, marca = ? WHERE id = ? AND aula_id = ?");
                $stmt->bind_param("sisii", $equipo['descripcion'], $equipo['cantidad'], $equipo['marca'], $id, $aula_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO equipos (descripcion, cantidad, marca, aula_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sisi", $equipo['descripcion'], $equipo['cantidad'], $equipo['marca'], $aula_id);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error al guardar equipo: " . $stmt->error);
            }
            $stmt->close();
        }

        $conn->commit();
        echo "success";

    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Error: Método no permitido");
}
?>
