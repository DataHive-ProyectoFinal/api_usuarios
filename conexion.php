<?php
//$host = "192.168.5.50"; //probar con localhost
//$user = "valentina.gahn";     // cambiar por valentina.gahn(mi usuario en la utu(creo))
//$pass = "56754233";         // mi CI es la pass
//$db   = "valentina_gahn"; // cambiar por valentina_gahn(o el nombre de mi bd en la utu)

$host = "localhost"; //esta es la config que me funciona loclamente
$user = "root";     
$pass = "";         
$db   = "cooperativa";

$conexion = new mysqli($host, $user, $pass, $db);



if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
