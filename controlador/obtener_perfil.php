<?php
require_once(__DIR__ . "/../conexion.php");

header("Content-Type: application/json");

$ci = $_GET["ci"] ?? "";

if (empty($ci)) {
    echo json_encode(["success" => false, "msg" => "CI no proporcionada"]);
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM perfil_socios WHERE id = ?");
$stmt->bind_param("s", $ci);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $perfil = $result->fetch_assoc();
    echo json_encode(["success" => true, "perfil" => $perfil]);
} else {
    echo json_encode(["success" => false, "msg" => "Perfil no encontrado"]);
}
?>
