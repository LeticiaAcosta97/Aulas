<?php
$host = "localhost";
$user = "root";  // Usuario de MySQL
$pass = "";      // Deja vacío si usas Laragon
$db = "sistema_aulas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

