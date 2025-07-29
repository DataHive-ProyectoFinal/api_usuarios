<?php
require_once 'conexion.php';

$sql = "SELECT * FROM solicitudes_ingreso WHERE estado = 'pendiente' ORDER BY fecha_solicitud DESC"; //obtenemos solicitudes pendientes desde la bd
$resultado = $conexion->query($sql); x

$solicitudes = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $solicitudes[] = $fila;
    }
}

echo json_encode($solicitudes);
$conexion->close();
?>
