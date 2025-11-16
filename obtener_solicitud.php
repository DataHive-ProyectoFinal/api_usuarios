<?php
// api_usuarios/obtener_solicitud.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'socio') {
        throw new Exception("Debe iniciar sesión como socio.");
    }

    require_once __DIR__ . '/config/conexion.php';
    require_once __DIR__ . '/modelo/SolicitudModel.php';

    $ci = $_SESSION['ci'];
    
    $solicitud = new Solicitud($conexion);
    $datos = $solicitud->obtenerPorCI($ci);

    if (!$datos) {
        throw new Exception("No se encontró la solicitud.");
    }

    echo json_encode([
        'success' => true,
        'solicitud' => $datos
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>