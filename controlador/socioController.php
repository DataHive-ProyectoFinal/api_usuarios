<?php
require_once __DIR__ . '/../modelo/socioModel.php';
require_once __DIR__ . '/../modelo/solicitudModel.php';

class SocioController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function aceptarSolicitud($ci) {
        $solicitudModel = new Solicitud($this->conexion);
        $datos = $solicitudModel->obtenerPorCI($ci);

        if (!$datos) {
            throw new Exception("No se encontró la solicitud.");
        }

        // Generar contraseña: primera letra del nombre + apellido + primeros 4 de la CI
        $nombre = $datos['nombre'];
        $apellido = explode(' ', $datos['apellido'])[0]; // primer apellido
        $contrasena = strtolower(substr($nombre, 0, 1) . $apellido . substr($ci, 0, 4));

        $socioModel = new Socio($this->conexion);
        if ($socioModel->crearSocio($ci, $nombre, $apellido, $datos['gmail'], $contrasena)) {
            $solicitudModel->actualizarEstado($ci, 'aceptada');
            $this->enviarCorreo($datos['gmail'], $nombre, $contrasena);
            return true;
        }
        return false;
    }

    public function rechazarSolicitud($ci) {
        $solicitudModel = new Solicitud($this->conexion);
        return $solicitudModel->actualizarEstado($ci, 'rechazada');
    }

    private function enviarCorreo($destinatario, $nombre, $contrasena) {
        $asunto = "Solicitud aceptada - Cooperativa Vista Linda";
        $mensaje = "Hola $nombre,\n\nTu solicitud fue aceptada.\nTu contraseña es: $contrasena\n\nPodrás iniciar sesión con tu cédula y esta contraseña.";
        $headers = "From: datahive2025@gmail.com";
        mail($destinatario, $asunto, $mensaje, $headers);
    }
}
?>
