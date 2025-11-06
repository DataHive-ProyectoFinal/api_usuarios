<?php
require_once(__DIR__ . "/../modelos/pago_inicial.php");
require_once(__DIR__ . "/../conexion.php");

$pago = new Pago($conexion);

$id = $_POST['id'];
$estado = $_POST['estado']; // 'aprobado' o 'rechazado'

if ($pago->actualizarEstado($id, $estado)) {
    if ($estado === 'aprobado') {
        // Actualizar el campo perfil_completo del socio a 1
        $sql = "UPDATE socios 
                JOIN pagos_iniciales ON socios.ci = pagos_iniciales.socio_ci
                SET socios.perfil_completo = 1
                WHERE pagos_iniciales.id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
