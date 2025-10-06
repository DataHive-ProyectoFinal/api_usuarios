<?php
require_once 'conexion.php';

$id = $_POST['id'];
$accion = $_POST['accion'];

if ($accion === 'aceptar') {
    $sql = "UPDATE solicitudes_ingreso SET estado = 'aceptada' WHERE id = $id";
    $conexion->query($sql);
    header("Location: asignar_contrasena.php?id=$id");
    exit();
} elseif ($accion === 'rechazar') {
    $sql = "UPDATE solicitudes_ingreso SET estado = 'rechazada' WHERE id = $id";
    $conexion->query($sql);
    header("Location: admin_solicitudes.php");
    exit();
}
?>

