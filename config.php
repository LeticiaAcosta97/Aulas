<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'sistema_aulas';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>

