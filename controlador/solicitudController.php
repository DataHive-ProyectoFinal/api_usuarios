<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../modelo/solicitudModel.php';

class SolicitudController {
    private $modelo;

    // Constructor: recibe la conexión y crea el modelo
    public function __construct($conexion) {
        $this->modelo = new Solicitud($conexion);
    }

    // Registrar solicitud (ya lo tenías)
    public function registrarSolicitud($post) {
        $campos = [
            'nombre', 'ci', 'fecha_nacimiento', 'gmail', 'telefono_celular',
            'direccion', 'cantidad_familia', 'ocupacion', 'ingreso_mensual', 'motivo_interes'
        ];

        foreach ($campos as $campo) {
            if (empty($post[$campo])) {
                throw new Exception("El campo $campo es obligatorio.");
            }
        }

        $datos = [
            ':nombre' => htmlspecialchars($post['nombre']),
            ':ci' => htmlspecialchars($post['ci']),
            ':fecha_nacimiento' => $post['fecha_nacimiento'],
            ':genero' => $post['genero'] ?? null,
            ':gmail' => htmlspecialchars($post['gmail']),
            ':telefono_celular' => $post['telefono_celular'],
            ':telefono_fijo' => $post['telefono_fijo'] ?? null,
            ':direccion' => htmlspecialchars($post['direccion']),
            ':cantidad_familia' => $post['cantidad_familia'],
            ':discapacidad' => $post['discapacidad'] ?? null,
            ':ocupacion' => $post['ocupacion'],
            ':ingreso_mensual' => $post['ingreso_mensual'],
            ':motivo_interes' => htmlspecialchars($post['motivo_interes']),
            ':autorizacion_datos' => isset($post['autorizacion_datos']) ? 1 : 0
        ];

        return $this->modelo->registrar($datos);
    }

    // Devuelve un mysqli_result con las pendientes
    public function listarPendientes() {
        return $this->modelo->listarPendientes();
    }


    // Devuelve una sola solicitud como array asociativo
    public function obtenerSolicitudPorCI($ci) {
        return $this->modelo->obtenerPorCI($ci);
    }
}



if (isset($_GET['action']) && $_GET['action'] === 'listar') {
    $controller = new SolicitudController($conexion);
    $solicitudes = $controller->listarPendientes();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($solicitudes);
    exit;
}


if (isset($_GET['ci'])) {
    // devuelve una sola solicitud y la contraseña generada
    $ci = $_GET['ci'];
    $controller = new SolicitudController($conexion);
    $sol = $controller->obtenerSolicitudPorCI($ci);

    if (!$sol) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Solicitud no encontrada']);
        exit;
    }

    // Generar contraseña según regla: 1ª letra del nombre + apellido + primeros 4 dígitos CI
    $partesNombre = explode(' ', trim($sol['nombre_completo']));
    $nombre = $partesNombre[0] ?? '';
    $apellido = $partesNombre[1] ?? '';
    $contrasena_generada = strtolower(substr($nombre, 0, 1) . $apellido . substr($sol['ci'], 0, 4));

    // añadir campo a la respuesta JSON
    $sol['contrasena_generada'] = $contrasena_generada;

    header('Content-Type: application/json');
    echo json_encode($sol);
    exit;
}
?>
