<?php
header("Content-Type: application/json");
require_once(__DIR__ . '/../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ci = $_POST['ci'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($ci) || empty($nombre) || empty($email) || empty($telefono) || empty($password)) {
        echo json_encode(["success" => false, "msg" => "Todos los campos son obligatorios"]);
        exit;
    }

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Insertar en la tabla perfil_socio
        $stmt = $conexion->prepare("INSERT INTO perfil_socio (ci, nombre, email, telefono, password_hash) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $ci, $nombre, $email, $telefono, $password_hash);
        $stmt->execute();

        // Marcar perfil como completo
        $update = $conexion->prepare("UPDATE socios SET perfil_completo = 1 WHERE ci = ?");
        $update->bind_param("s", $ci);
        $update->execute();

        echo json_encode([
            "success" => true,
            "msg" => "Perfil completado correctamente"
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "msg" => "Error al guardar el perfil: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["success" => false, "msg" => "Método no permitido"]);
}
