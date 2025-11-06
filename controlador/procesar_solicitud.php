<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/crear_usuarioController.php';
require_once __DIR__ . '/../modelo/solicitudModel.php';

$modelo = new Solicitud($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['action'] ?? '';

    if ($accion === 'aceptar') {
        crearUsuario($_POST['ci'], $_POST['contrasena']);
    } elseif ($accion === 'rechazar') {
        $ci = $_POST['ci'];
        $modelo->actualizarEstado($ci, 'rechazado');
        echo "Solicitud rechazada correctamente.";
    } else {
        echo "Acción no válida.";
    }
}
?>
