<?php
// api_usuarios/marcar_notificacion_leida.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'socio') {
        throw new Exception("Debe iniciar sesión como socio.");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    require_once __DIR__ . '/../shared/modelo/PagoModel.php';
    require_once __DIR__ . '/config/conexion.php';

    $ci_socio = $_SESSION['ci'];
    $id_pago = $_POST['id_pago'] ?? null;
    $marcar_todas = $_POST['marcar_todas'] ?? false;

    $pagoModel = new PagoModel($conexion);

    if ($marcar_todas) {
        // Marcar todas las notificaciones como leídas
        $pagoModel->marcarTodasNotificacionesLeidas($ci_socio);
        
        echo json_encode([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas'
        ]);
    } else {
        // Marcar una notificación específica
        if (!$id_pago) {
            throw new Exception("ID de pago no proporcionado.");
        }

        $pagoModel->marcarNotificacionLeida($id_pago, $ci_socio);
        
        echo json_encode([
            'success' => true,
            'message' => 'Notificación marcada como leída'
        ]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>