<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include 'conexion.php';

// Verificamos que sea POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturamos y sanitizamos los datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $ci = $_POST['ci'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $gmail = $_POST['gmail'] ?? '';
    $telefono_celular = $_POST['telefono_celular'] ?? '';
    $telefono_fijo = $_POST['telefono_fijo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $cantidad_familia = $_POST['cantidad_familia'] ?? 0;
    $discapacidad = $_POST['discapacidad'] ?? '';
    $ocupacion = $_POST['ocupacion'] ?? '';
    $ingreso_mensual = $_POST['ingreso_mensual'] ?? '';
    $motivo_interes = $_POST['motivo_interes'] ?? '';
    $autorizacion_datos = isset($_POST['autorizacion_datos']) ? 1 : 0;

    // Preparar consulta SQL
    $sql = "INSERT INTO solicitudes_ingreso (
        nombre_completo, ci, fecha_nacimiento, genero, gmail, 
        telefono_celular, telefono_fijo, direccion, cantidad_familia, 
        discapacidad_cargo, ocupacion, ingreso, motivo_interes, autorizacion_datos
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssissssi", $nombre, $ci, $fecha_nacimiento, $genero, $gmail, $telefono_celular, $telefono_fijo, $direccion, $cantidad_familia, $discapacidad, $ocupacion, $ingreso_mensual, $motivo_interes, $autorizacion_datos);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([ "Solicitud enviada con exito"]);
    } else {
        http_response_code(500);
        echo json_encode(["Error al registrar solicitud", "error" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
} else {
    http_response_code(405);
    echo json_encode(["Método no permitido"]);
}
?>
