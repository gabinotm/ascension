<?php
require_once 'config/conexion.php';
$mes = (int)$_GET['mes'];
$anio = (int)$_GET['anio'];

$query = "SELECT DAY(fecha_inicio) as dia, titulo, descripcion, color 
          FROM publicaciones WHERE MONTH(fecha_inicio) = $mes AND YEAR(fecha_inicio) = $anio";
$res = mysqli_query($conn, $query);
$eventos = [];
while($row = mysqli_fetch_assoc($res)) { $eventos[$row['dia']][] = $row; }
echo json_encode($eventos);
?>