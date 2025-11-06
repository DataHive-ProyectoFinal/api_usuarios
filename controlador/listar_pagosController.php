<?php
require_once(__DIR__ . "/../modelos/pago_inicial.php");
require_once(__DIR__ . "/../conexion.php");

$pago = new Pago($conexion);
$resultado = $pago->listarPendientes();

$pagos = [];
while ($fila = $resultado->fetch_assoc()) {
    $pagos[] = $fila;
}

echo json_encode($pagos);
?>
