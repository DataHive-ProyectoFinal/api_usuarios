<?php
// api_usuarios/login.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    require_once __DIR__ . '/config/conexion.php';
    require_once __DIR__ . '/modelo/SocioModel.php';
    require_once __DIR__ . '/modelo/AdminModel.php';

    $ci = $_POST['ci'] ?? null;
    $contrasena = $_POST['contrasena'] ?? null;

    if (empty($ci) || empty($contrasena)) {
        throw new Exception("Debe completar todos los campos.");
    }

    // Intentar login como socio
    $socioModel = new SocioModel($conexion);
    $socio = $socioModel->verificarCredenciales($ci, $contrasena);

    if ($socio) {
        $_SESSION['ci'] = $socio['ci'];
        $_SESSION['nombre'] = $socio['nombre_completo'];
        $_SESSION['tipo_usuario'] = 'socio';
        $_SESSION['perfil_completo'] = $socio['perfil_completo'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'tipo_usuario' => 'socio',
            'perfil_completo' => $socio['perfil_completo'],
            'nombre' => $socio['nombre_completo']
        ]);
        exit;
    }

    // Si no es socio, intentar login como admin
    $adminModel = new AdminModel($conexion);
    $admin = $adminModel->verificarCredenciales($ci, $contrasena);

    if ($admin) {
        $_SESSION['ci'] = $admin['ci'];
        $_SESSION['nombre'] = $admin['nombre'];
        $_SESSION['tipo_usuario'] = 'admin';
        
        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'tipo_usuario' => 'admin',
            'nombre' => $admin['nombre']
        ]);
        exit;
    }

    throw new Exception("Cédula o contraseña incorrecta.");

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>