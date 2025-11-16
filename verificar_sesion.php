<?php
// api_usuarios/verificar_sesion.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['tipo_usuario'])) {
        throw new Exception("No hay sesión activa.");
    }

    echo json_encode([
        'success' => true,
        'tipo_usuario' => $_SESSION['tipo_usuario'],
        'ci' => $_SESSION['ci'] ?? null,
        'nombre' => $_SESSION['nombre'] ?? null,
        'perfil_completo' => $_SESSION['perfil_completo'] ?? null
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>