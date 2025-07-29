<?php
include 'conexion.php';

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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

    $sql = "INSERT INTO solicitudes_ingreso (
        nombre_completo, ci, fecha_nacimiento, genero, gmail, 
        telefono_celular, telefono_fijo, direccion, cantidad_familia, 
        discapacidad_cargo, ocupacion, ingreso, motivo_interes, autorizacion_datos
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssissssi", $nombre, $ci, $fecha_nacimiento, $genero, $gmail, $telefono_celular, $telefono_fijo, $direccion, $cantidad_familia, $discapacidad, $ocupacion, $ingreso_mensual, $motivo_interes, $autorizacion_datos);

    if ($stmt->execute()) {
        $mensaje = " Solicitud enviada con éxito. Pronto recibirás novedades por correo.";
        $tipo = "success";
    } else {
        $mensaje = " Error al registrar la solicitud: " . $stmt->error;
        $tipo = "error";
    }

    $stmt->close();
    $conexion->close();
} else {
    $mensaje = "⚠️ Método no permitido.";
    $tipo = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Resultado del Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full text-center">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Estado de la Solicitud</h1>

        <?php if ($tipo === "success"): ?>
            <p class="text-green-700 bg-green-100 p-4 rounded-lg mb-6"><?= htmlspecialchars($mensaje) ?></p>
        <?php else: ?>
            <p class="text-red-700 bg-red-100 p-4 rounded-lg mb-6"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <!-- 🔗 Acá va el link al Home -->
        <a href="/Frontend/landingPage/Home.html"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
           Volver al inicio
        </a>
    </div>
</body>
</html>
