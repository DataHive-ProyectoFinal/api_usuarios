<?php
require_once(__DIR__ . "/../conexion.php");

class Pago {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Crear un pago nuevo
    public function crear($ci, $monto, $comprobante) {
        $sql = "INSERT INTO pagos_iniciales (socio_ci, monto, comprobante) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sds", $ci, $monto, $comprobante);
        return $stmt->execute();
    }

    // Listar pagos pendientes para el admin
    public function listarPendientes() {
        $sql = "SELECT * FROM pagos_iniciales WHERE estado = 'pendiente'";
        return $this->conexion->query($sql);
    }

    // Cambiar estado del pago
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE pagos_iniciales SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
}
?>
