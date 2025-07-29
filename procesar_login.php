<?php
session_start();
require_once 'conexion.php';

$ci = $_POST['ci'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE ci = ?");
$stmt->bind_param("s", $ci);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Login exitoso
        $_SESSION['ci'] = $usuario['ci'];
        $_SESSION['rol'] = 'socio'; 

        header("Location: interfaz_socio.php");
        exit;
    } else {
        $error = "Contraseña incorrecta.";
    }
} else {
    $error = "Cédula no registrada.";
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white p-6 rounded-xl shadow-md max-w-md w-full text-center">
    <h2 class="text-xl font-bold text-red-700 mb-4">Error al iniciar sesión</h2>
    <p class="text-gray-700 mb-6"><?= htmlspecialchars($error) ?></p>
    <a href="login.php"
       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
      Volver al login
    </a>
  </div>
</body>
</html>
