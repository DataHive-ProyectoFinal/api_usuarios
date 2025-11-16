<?php
// api_usuarios/modelo/SolicitudModel.php

class Solicitud {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function existeCI($ci) {
        $sql = "SELECT COUNT(*) as total FROM solicitudes_ingreso WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }

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
            "sissssssiisssi",
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

    public function listarPendientes() {
        $sql = "SELECT * FROM solicitudes_ingreso WHERE estado = 'pendiente' ORDER BY fecha_solicitud DESC";
        $resultado = $this->conexion->query($sql);
        $solicitudes = [];
        
        while ($fila = $resultado->fetch_assoc()) {
            $solicitudes[] = $fila;
        }
        
        return $solicitudes;
    }

    public function obtenerPorCI($ci) {
        $stmt = $this->conexion->prepare("SELECT * FROM solicitudes_ingreso WHERE ci = ?");
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarEstado($ci, $estado) {
        $stmt = $this->conexion->prepare("UPDATE solicitudes_ingreso SET estado = ? WHERE ci = ?");
        $stmt->bind_param("si", $estado, $ci);
        return $stmt->execute();
    }
}
?>