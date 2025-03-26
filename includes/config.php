<?php
$host = "localhost";
$user = "root"; // Cambiar si tienes otro usuario en MySQL
$password = ""; // Cambiar si tienes contraseña en MySQL
$dbname = "sistema_aulas"; // Asegúrate de que este sea el nombre correcto

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
