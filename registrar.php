<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/controlador/solicitudController.php';

try {
    // Crear instancia del controlador pasándole la conexión
    $controller = new SolicitudController($conexion);

    // Llamar al método para registrar la solicitud
    $resultado = $controller->registrarSolicitud($_POST);

    if ($resultado) {
        header("Location: ../api_usuarios/vistas/mensaje_exito.html");
    } else {
        header("Location: ../api_usuarios/vistas/mensaje_error.html");
    }
} catch (Exception $e) {
    // Si ocurre un error de validación o de base de datos
    header("Location: v/vistas/mensaje_error.html?error=" . urlencode($e->getMessage()));
}
?>
