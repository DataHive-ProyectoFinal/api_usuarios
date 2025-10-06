<?php
require_once 'conexion.php';

// Si se hizo clic en "Rechazar"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazar_id'])) {
    $rechazar_id = $_POST['rechazar_id'];
    $stmt = $conexion->prepare("UPDATE solicitudes_ingreso SET estado = 'rechazado' WHERE id = ?");
    $stmt->bind_param("i", $rechazar_id);
    $stmt->execute();
}

// Traer solicitudes pendientes
$sql = "SELECT * FROM solicitudes_ingreso WHERE estado = 'pendiente'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Ingreso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Solicitudes Pendientes</h1>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border text-sm text-left">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-3 py-2 border">Nombre Completo</th>
                            <th class="px-3 py-2 border">CI</th>
                            <th class="px-3 py-2 border">Fecha Nacimiento</th>
                            <th class="px-3 py-2 border">Género</th>
                            <th class="px-3 py-2 border">Email</th>
                            <th class="px-3 py-2 border">Tel. Celular</th>
                            <th class="px-3 py-2 border">Tel. Fijo</th>
                            <th class="px-3 py-2 border">Dirección</th>
                            <th class="px-3 py-2 border">Cant. Familia</th>
                            <th class="px-3 py-2 border">Discapacidad a Cargo</th>
                            <th class="px-3 py-2 border">Ocupación</th>
                            <th class="px-3 py-2 border">Ingreso</th>
                            <th class="px-3 py-2 border">Motivo de Interés</th>
                            <th class="px-3 py-2 border">Autorización Datos</th>
                            <th class="px-3 py-2 border">Fecha Solicitud</th>
                            <th class="px-3 py-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['nombre_completo']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['ci']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['fecha_nacimiento']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['genero']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['gmail']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['telefono_celular']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['telefono_fijo']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['direccion']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['cantidad_familia']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['discapacidad_cargo']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['ocupacion']) ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['ingreso']) ?></td>
                                <td class="px-3 py-2 border"><?= nl2br(htmlspecialchars($fila['motivo_interes'])) ?></td>
                                <td class="px-3 py-2 border"><?= $fila['autorizacion_datos'] ? 'Sí' : 'No' ?></td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($fila['fecha_solicitud']) ?></td>
                                <td class="px-3 py-2 border">
                                    <div class="flex gap-2 items-center">
                                        <a href="/prueba%20proyecto/api_usuarios/asignar_contrasena.php?id=<?= $fila['id'] ?>"
                                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            Aceptar
                                        </a>
                                        <form method="POST" onsubmit="return confirm('¿Seguro que deseas rechazar esta solicitud?')">
                                            <input type="hidden" name="rechazar_id" value="<?= $fila['id'] ?>">
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                Rechazar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600">No hay solicitudes pendientes.</p>
        <?php endif; ?>
    </div>
</body>
</html>
