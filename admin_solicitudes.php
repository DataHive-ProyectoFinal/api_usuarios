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
    <style>
        table {
            width: 100%;
            
            margin-bottom: 2rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: inline;
        }
        .acciones {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h1>Solicitudes Pendientes</h1>

    <?php if ($resultado->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Email</th>
                    <th>Tel. Celular</th>
                    <th>Tel. Fijo</th>
                    <th>Dirección</th>
                    <th>Cant. Familia</th>
                    <th>Discapacidad a Cargo</th>
                    <th>Ocupación</th>
                    <th>Ingreso</th>
                    <th>Motivo de Interés</th>
                    <th>Autorización Datos</th>
                    <th>Fecha Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($fila['ci']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_nacimiento']) ?></td>
                        <td><?= htmlspecialchars($fila['genero']) ?></td>
                        <td><?= htmlspecialchars($fila['gmail']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono_celular']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono_fijo']) ?></td>
                        <td><?= htmlspecialchars($fila['direccion']) ?></td>
                        <td><?= htmlspecialchars($fila['cantidad_familia']) ?></td>
                        <td><?= htmlspecialchars($fila['discapacidad_cargo']) ?></td>
                        <td><?= htmlspecialchars($fila['ocupacion']) ?></td>
                        <td><?= htmlspecialchars($fila['ingreso']) ?></td>
                        <td><?= nl2br(htmlspecialchars($fila['motivo_interes'])) ?></td>
                        <td><?= $fila['autorizacion_datos'] ? 'Sí' : 'No' ?></td>
                        <td><?= htmlspecialchars($fila['fecha_solicitud']) ?></td>
                        <td class="acciones">
                            <a href="asignar_contrasena.php?id=<?= $fila['id'] ?>">Aceptar</a>
                            <form method="POST">
                                <input type="hidden" name="rechazar_id" value="<?= $fila['id'] ?>">
                                <button type="submit" onclick="return confirm('¿Seguro que deseas rechazar esta solicitud?')">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay solicitudes pendientes.</p>
    <?php endif; ?>
</body>
</html>
