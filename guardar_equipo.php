<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $nro_factura = $_POST['nro_factura'];
        $descripcion = $_POST['descripcion'];
        $cantidad = intval($_POST['cantidad']);
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];

        // Validar datos
        if (empty($nro_factura) || empty($descripcion) || $cantidad <= 0) {
            throw new Exception("Todos los campos son obligatorios");
        }

        // Consulta actualizada según la estructura de la tabla
        $sql = "INSERT INTO equipos (nombre, descripcion, tipo, marca, modelo, estado, nro_serie, nro_factura) 
                VALUES (?, ?, ?, ?, ?, 'Sin Asignar', ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Insertar la cantidad especificada de equipos
        for($i = 0; $i < $cantidad; $i++) {
            $nombre = $descripcion; // El nombre será igual a la descripción
            $tipo = $descripcion; // El tipo será igual a la descripción
            $nro_serie = $nro_factura . "-" . str_pad(($i + 1), 3, "0", STR_PAD_LEFT);
            
            $stmt->bind_param("sssssss", 
                $nombre,
                $descripcion, 
                $tipo,
                $marca, 
                $modelo,
                $nro_serie,
                $nro_factura
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error al insertar el equipo: " . $stmt->error);
            }
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
