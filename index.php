<?php
session_start();
include 'includes/config.php';

$result = $conn->query("SHOW TABLES LIKE 'aulas'");
if ($result->num_rows == 0) {
    die("Error: La tabla 'aulas' no existe. Asegúrate de crearla en la base de datos.");
}

header("Location: login.php");
exit();
?>
