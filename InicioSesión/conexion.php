<?php
include ("configuración.php");
$conexion = new mysqli($servername,$username,$password,$bd);

if (mysqli_connect_error()) {
    echo "No conectado"; mysqli_connect_error ();
    exit();
}else {
}
?>