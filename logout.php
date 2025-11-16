<?php
/**
 * Cerrar Sesión
 * Ruta: /api_usuarios/logout.php
 */

header('Content-Type: application/json');
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destruir la sesión
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Sesión cerrada correctamente'
]);
?>