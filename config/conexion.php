<?php
// api_usuarios/config/conexion.php

$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'cooperativa_vista_linda';

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Error de conexión: ' . $conexion->connect_error
    ]));
}

$conexion->set_charset("utf8mb4");
?>