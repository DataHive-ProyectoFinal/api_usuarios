<?php
class Solicitud {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    //  Inserta una nueva solicitud en la base de datos
    public function registrar($datos) {
        $sql = "INSERT INTO solicitudes_ingreso 
            (nombre_completo, ci, fecha_nacimiento, genero, gmail, telefono_celular, telefono_fijo,
            direccion, cantidad_familia, discapacidad_cargo, ocupacion, ingreso, 
            motivo_interes, autorizacion_datos, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')";

        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param(
            "ssssssssissssi",
            $datos[':nombre'],
            $datos[':ci'],
            $datos[':fecha_nacimiento'],
            $datos[':genero'],
            $datos[':gmail'],
            $datos[':telefono_celular'],
            $datos[':telefono_fijo'],
            $datos[':direccion'],
            $datos[':cantidad_familia'],
            $datos[':discapacidad'],
            $datos[':ocupacion'],
            $datos[':ingreso_mensual'],
            $datos[':motivo_interes'],
            $datos[':autorizacion_datos']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar la solicitud: " . $stmt->error);
        }

        return true;
    }

    //  Devuelve todas las solicitudes pendientes
      public function listarPendientes() {
            $sql = "SELECT * FROM solicitudes_ingreso WHERE estado = 'pendiente'";
            $resultado = $this->conexion->query($sql);
            $solicitudes = [];
            while ($fila = $resultado->fetch_assoc()) {
                $solicitudes[] = $fila;
            }
            return $solicitudes;
        }

    //  Devuelve una solicitud específica según la cédula
    public function obtenerPorCI($ci) {
        $stmt = $this->conexion->prepare("SELECT * FROM solicitudes_ingreso WHERE ci = ?");
        $stmt->bind_param("s", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }


    //  Cambia el estado de una solicitud (aceptada / rechazada)
    public function actualizarEstado($ci, $estado) {
        $stmt = $this->conexion->prepare("UPDATE solicitudes_ingreso SET estado = ? WHERE ci = ?");
        $stmt->bind_param("ss", $estado, $ci);
        return $stmt->execute();
    }

    

}
?>
