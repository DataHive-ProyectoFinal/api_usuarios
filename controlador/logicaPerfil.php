<?php
session_start();
header('Content-Type: application/json');   
require_once "conexion.php";

if (!isset($_SESSION['ci'])) {
    echo json_encode(["error" => "No autenticado"]);
    http_response_code(401);
    exit;
}

$ci = $_SESSION['ci'];
$sql = "SELECT nombre_completo, gmail FROM solicitudes_ingreso WHERE ci = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $ci);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();


echo json_encode($usuario, JSON_UNESCAPED_UNICODE);
