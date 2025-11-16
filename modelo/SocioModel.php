<?php
// api_usuarios/modelo/SocioModel.php

class SocioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // ========== CREAR SOCIO DESDE SOLICITUD ==========
    public function crearDesdeSolicitud($solicitud, $contrasena_hash) {
        $sql = "INSERT INTO socios 
                (ci, contrasena, nombre_completo, gmail, telefono_celular, direccion, 
                fecha_ingreso, perfil_completo, pago_inicial_aprobado, activo)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 0, 0, 1)";
        
        $stmt = $this->conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación: " . $this->conexion->error);
        }
        
        $stmt->bind_param(
            "isssss",
            $solicitud['ci'],
            $contrasena_hash,
            $solicitud['nombre_completo'],
            $solicitud['gmail'],
            $solicitud['telefono_celular'],
            $solicitud['direccion']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error al crear socio: " . $stmt->error);
        }
        
        return true;
    }

    // ========== OBTENER SOCIO POR CI ==========
    public function obtenerPorCI($ci) {
        $sql = "SELECT * FROM socios WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        return $resultado->fetch_assoc();
    }

    // ========== VERIFICAR CREDENCIALES ==========
    public function verificarCredenciales($ci, $contrasena) {
        $socio = $this->obtenerPorCI($ci);
        
        if (!$socio) {
            return null;
        }
        
        if (password_verify($contrasena, $socio['contrasena'])) {
            return $socio;
        }
        
        return null;
    }

    // ========== ACTUALIZAR PERFIL ==========
    public function actualizarPerfil($ci, $datos) {
        $sql = "UPDATE socios SET 
                nombre_completo = ?,
                gmail = ?,
                telefono_celular = ?,
                direccion = ?
                WHERE ci = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $datos['nombre_completo'],
            $datos['gmail'],
            $datos['telefono_celular'],
            $datos['direccion'],
            $ci
        );
        
        return $stmt->execute();
    }

    // ========== CAMBIAR CONTRASEÑA ==========
    public function cambiarContrasena($ci, $nueva_contrasena_hash) {
        $sql = "UPDATE socios SET contrasena = ? WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nueva_contrasena_hash, $ci);
        
        return $stmt->execute();
    }

    // ========== LISTAR TODOS LOS SOCIOS ==========
    public function listarTodos($filtro = null) {
        $sql = "SELECT * FROM socios";
        
        if ($filtro === 'pendiente') {
            $sql .= " WHERE perfil_completo = 0";
        } elseif ($filtro === 'activo') {
            $sql .= " WHERE perfil_completo = 1 AND activo = 1";
        }
        
        $sql .= " ORDER BY fecha_ingreso DESC";
        
        $resultado = $this->conexion->query($sql);
        
        $socios = [];
        while ($fila = $resultado->fetch_assoc()) {
            // No incluir la contraseña
            unset($fila['contrasena']);
            $socios[] = $fila;
        }
        
        return $socios;
    }

    // ========== CONTAR SOCIOS ==========
    public function contarSocios() {
        $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN perfil_completo = 0 THEN 1 END) as pendientes,
                COUNT(CASE WHEN perfil_completo = 1 AND activo = 1 THEN 1 END) as activos
                FROM socios";
        
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_assoc();
    }
}
?>