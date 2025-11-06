<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/controlador/socioController.php';

try {
    $controller = new SocioController($conexion);
    $ci = $_POST['ci'];
    $controller->rechazarSolicitud($ci);
    header("Location: vistas/mensaje_exito.html");
} catch (Exception $e) {
    header("Location: vistas/mensaje_error.html?error=" . urlencode($e->getMessage()));
}
?>
