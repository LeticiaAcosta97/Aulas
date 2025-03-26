<?php
include("config.php");

$aula_id = $_GET['aula_id'];

$query = "DELETE FROM asignaciones WHERE aula_id = $aula_id";
$conn->query($query);

header("Location: asignacion_aulas.php");
?>
