<?php
session_start();
if (!isset($_SESSION['ci'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$ci = $_SESSION['ci'];
$stmt = $conexion->prepare("SELECT * FROM solicitudes_ingreso WHERE ci = ?");
$stmt->bind_param("s", $ci);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Enviar los datos como JSON
header('Content-Type: application/json');
echo json_encode($usuario);
