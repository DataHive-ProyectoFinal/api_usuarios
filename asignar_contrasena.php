<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID inválido");
}

$stmt = $conexion->prepare("SELECT nombre_completo, ci, gmail FROM solicitudes_ingreso WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Solicitud no encontrada");
}

$solicitud = $result->fetch_assoc();
$error = "";
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasena = $_POST['contrasena'] ?? '';
    if (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $ci = $solicitud['ci'];

        // Verificar si el usuario ya existe
        $stmtCheck = $conexion->prepare("SELECT ci FROM usuarios WHERE ci = ?");
        $stmtCheck->bind_param("s", $ci);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $error = "El usuario con esa cédula ya existe.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmtInsert = $conexion->prepare("INSERT INTO usuarios (ci, contrasena) VALUES (?, ?)");
            $stmtInsert->bind_param("ss", $ci, $hash);

            if ($stmtInsert->execute()) {
                $stmtUpdate = $conexion->prepare("UPDATE solicitudes_ingreso SET estado='aceptado' WHERE id=?");
                $stmtUpdate->bind_param("i", $id);
                $stmtUpdate->execute();

                $mensaje = "Usuario creado correctamente. Aquí podrías enviar el mail.";
            } else {
                $error = "Error al crear usuario: " . $conexion->error;
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Contraseña</title>
</head>
<body>
    <h1>Asignar contraseña para <?= htmlspecialchars($solicitud['nombre_completo']) ?></h1>
    <p>Email: <?= htmlspecialchars($solicitud['gmail']) ?></p>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($mensaje)): ?>
        <p style="color:green;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" id="contrasena" required minlength="6"><br><br>
        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
