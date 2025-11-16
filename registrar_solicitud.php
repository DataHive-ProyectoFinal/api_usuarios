<?php
// api_usuarios/registrar_solicitud.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    require_once __DIR__ . '/config/conexion.php';
    require_once __DIR__ . '/modelo/SolicitudModel.php';

    $solicitud = new Solicitud($conexion);

    // Verificar que no exista el CI
    $ci = $_POST['ci'] ?? null;
    
    if (!$ci) {
        throw new Exception("La cédula es obligatoria.");
    }

    if ($solicitud->existeCI($ci)) {
        throw new Exception("Ya existe una solicitud con esta cédula.");
    }

    // Preparar datos (SIN contraseña)
    $datos = [
        ':nombre' => $_POST['nombre_completo'] ?? null,
        ':ci' => $ci,
        ':fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
        ':genero' => $_POST['genero'] ?? null,
        ':gmail' => $_POST['gmail'] ?? null,
        ':telefono_celular' => $_POST['telefono_celular'] ?? null,
        ':telefono_fijo' => $_POST['telefono_fijo'] ?? '',
        ':direccion' => $_POST['direccion'] ?? null,
        ':cantidad_familia' => $_POST['cantidad_familia'] ?? null,
        ':discapacidad' => isset($_POST['discapacidad_cargo']) ? 1 : 0,
        ':ocupacion' => $_POST['ocupacion'] ?? null,
        ':ingreso_mensual' => $_POST['ingreso'] ?? null,
        ':motivo_interes' => $_POST['motivo_interes'] ?? null,
        ':autorizacion_datos' => isset($_POST['autorizacion_datos']) ? 1 : 0
    ];

    // Validaciones básicas
    $campos_requeridos = [':nombre', ':ci', ':fecha_nacimiento', ':genero', ':gmail', 
                         ':telefono_celular', ':direccion', ':cantidad_familia', 
                         ':ocupacion', ':ingreso_mensual', ':motivo_interes'];

    foreach ($campos_requeridos as $campo) {
        if (empty($datos[$campo])) {
            throw new Exception("Todos los campos obligatorios deben ser completados.");
        }
    }

    if ($datos[':autorizacion_datos'] != 1) {
        throw new Exception("Debe autorizar el uso de datos personales.");
    }

    // Registrar solicitud
    $solicitud->registrar($datos);

    echo json_encode([
        'success' => true,
        'message' => 'Solicitud registrada correctamente. Será revisada por un administrador.'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>