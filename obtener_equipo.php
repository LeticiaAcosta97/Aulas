<?php
include("config.php");

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM equipos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$equipo = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($equipo);