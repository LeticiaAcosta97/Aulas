<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipo_id = $_POST["equipo_id"];
    $descripcion = $_POST["descripcion"];
    $cantidad = $_POST["cantidad"];
    $marca = $_POST["marca"];

    $query = "UPDATE equipos SET descripcion = ?, cantidad = ?, marca = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisi", $descripcion, $cantidad, $marca, $equipo_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
$conn->close();
?>
