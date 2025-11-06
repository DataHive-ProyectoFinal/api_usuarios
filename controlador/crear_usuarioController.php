<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../modelo/solicitudModel.php';

function crearUsuario($ci, $contrasena) {
    global $conexion;

    $modelo = new Solicitud($conexion);
    $solicitud = $modelo->obtenerPorCI($ci);

    if (!$solicitud) {
        die("No se encontró la solicitud con CI $ci.");
    }

    // Solo necesitamos estos datos
    $gmail = $solicitud['gmail'];
    $nombre = $solicitud['nombre_completo'];

    // Hashear la contraseña antes de guardarla
    $password_hash = password_hash($contrasena, PASSWORD_BCRYPT);

    //  Inserta en la tabla SOCIOS 
    $sql = "INSERT INTO socios (ci, contrasena) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }

    $stmt->bind_param("ss", $ci, $password_hash);

    if ($stmt->execute()) {
        // Actualizar el estado en solicitudes
        $modelo->actualizarEstado($ci, 'aceptado');

        // Enviar correo con los datos de acceso
        $asunto = "Tu solicitud fue aceptada";
        $mensaje = "Hola $nombre,\n\nTu registro fue aceptado.\nTu usuario es tu CI: $ci\nTu contraseña es: $contrasena\n\nCooperativa Vista Linda";
        @mail($gmail, $asunto, $mensaje, "From: datahive2025@gmail.com");

        echo "Socio creado y correo enviado correctamente.";
    } else {
        echo "Error al crear socio: " . $stmt->error;
    }
}
?>
