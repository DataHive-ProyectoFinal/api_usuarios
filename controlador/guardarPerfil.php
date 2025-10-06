<?php
session_start();
if (!isset($_SESSION['ci'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$ci = $_SESSION['ci'];

// Obtener datos del formulario
$nombre = $_POST['nombre_completo'] ?? '';
$email = $_POST['gmail'] ?? '';
$genero = $_POST['genero'] ?? '';
$cel = $_POST['telefono_celular'] ?? '';
$fijo = $_POST['telefono_fijo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$familia = $_POST['cantidad_familia'] ?? '';
$discapacidad = $_POST['discapacidad_cargo'] ?? '';
$ocupacion = $_POST['ocupacion'] ?? '';
$ingreso = $_POST['ingreso'] ?? '';

$stmt = $conexion->prepare("UPDATE solicitudes_ingreso 
    SET nombre_completo=?, gmail=?, genero=?, telefono_celular=?, telefono_fijo=?, direccion=?, cantidad_familia=?, discapacidad_cargo=?, ocupacion=?, ingreso=?
    WHERE ci=?");
$stmt->bind_param("ssssssissss", $nombre, $email, $genero, $cel, $fijo, $direccion, $familia, $discapacidad, $ocupacion, $ingreso, $ci);

if ($stmt->execute()) {
    header("Location: logicaPerfil.php?msg=logicaEditar.php");
} else {
    echo "Error al actualizar: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
