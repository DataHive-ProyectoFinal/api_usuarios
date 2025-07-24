<?php
// conexion.php
require_once 'configuracion.php'; // Importa los datos de conexión

$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
