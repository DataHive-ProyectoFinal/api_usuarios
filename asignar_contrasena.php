<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;
//if (!$id) {
//    die("ID inválido");
//}

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

// Generar contraseña automáticamente
$nombreCompleto = strtolower($solicitud['nombre_completo']);
$nombreParts = explode(' ', $nombreCompleto);
$primeraLetraNombre = substr($nombreParts[0], 0, 1);
$apellido = isset($nombreParts[1]) ? $nombreParts[1] : 'apellido';
$ci = preg_replace('/[^0-9]/', '', $solicitud['ci']); // Eliminar guiones u otros símbolos
$ultimos3 = substr($ci, -4, 3); // Evita el dígito verificador

$contrasenaGenerada = $primeraLetraNombre . $apellido . $ultimos3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hash = password_hash($contrasenaGenerada, PASSWORD_DEFAULT);

    // Verificar si ya existe
    $stmtCheck = $conexion->prepare("SELECT ci FROM usuarios WHERE ci = ?");
    $stmtCheck->bind_param("s", $solicitud['ci']);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $error = "El usuario con esa cédula ya existe.";
    } else {
        $stmtInsert = $conexion->prepare("INSERT INTO usuarios (ci, contrasena) VALUES (?, ?)");
        $stmtInsert->bind_param("ss", $solicitud['ci'], $hash);

        if ($stmtInsert->execute()) {
            $stmtUpdate = $conexion->prepare("UPDATE solicitudes_ingreso SET estado='aceptado' WHERE id=?");
            $stmtUpdate->bind_param("i", $id);
            $stmtUpdate->execute();

            // Envío de correo temporalmente deshabilitado
        $mensaje = "Usuario creado correctamente. <br>El envío automático de correo está deshabilitado temporalmente. <br>Podés copiar la contraseña generada y enviarla manualmente a: " . htmlspecialchars($solicitud['gmail']);


        } else {
            $error = "Error al crear usuario: " . $conexion->error;
        }
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asignar Contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>



<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">
      Asignar contraseña para <?= htmlspecialchars($solicitud['nombre_completo']) ?>
    </h1>

    <div class="space-y-2">
      <p><strong>Email:</strong> <?= htmlspecialchars($solicitud['gmail']) ?></p>
      <p><strong>Cédula:</strong> <?= htmlspecialchars($solicitud['ci']) ?></p>
      <p class="text-sm text-gray-600">
        Contraseña sugerida: primera letra del nombre + apellido + últimos 3 dígitos de la cédula (sin verificador)
      </p>

      <div class="flex items-center gap-4">
        <input id="contrasenaGenerada" type="text"
          class="border rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 font-mono"
          value="<?= htmlspecialchars($contrasenaGenerada) ?>" readonly />
        <button onclick="copiarContrasena()"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          Copiar
        </button>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <p class="text-red-600 bg-red-100 p-3 rounded-lg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($mensaje)): ?>
      <p class="text-green-600 bg-green-100 p-3 rounded-lg"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <label for="contrasena" class="block text-gray-700 font-medium">Escriba la contaseña:</label>
      <input type="text" name="contrasena" id="contrasena" minlength="6"
        value="<?= htmlspecialchars($contrasenaGenerada) ?>"
        class="w-full border px-3 py-2 rounded-lg bg-white" required />

      <button type="submit"
        class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition font-semibold">
        Crear Usuario
      </button>
    </form>
  </div>

  <script>
    function copiarContrasena() {
      const input = document.getElementById('contrasenaGenerada');
      input.select();
      input.setSelectionRange(0, 99999);
      document.execCommand('copy');
      alert('Contraseña copiada al portapapeles');
    }
  </script>
</body>



</html>
