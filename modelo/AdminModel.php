<?php
// api_usuarios/modelo/AdminModel.php

class AdminModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // ========== VERIFICAR CREDENCIALES ==========
    public function verificarCredenciales($ci, $contrasena) {
        $admin = $this->obtenerPorCI($ci);
        
        if (!$admin) {
            return null;
        }

        if (!$admin['activo']) {
            return null;
        }
        
        if (password_verify($contrasena, $admin['contrasena'])) {
            return $admin;
        }
        
        return null;
    }

    // ========== OBTENER ADMIN POR CI ==========
    public function obtenerPorCI($ci) {
        $sql = "SELECT * FROM administradores WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        return $resultado->fetch_assoc();
    }

    // ========== CREAR ADMIN ==========
    public function crear($ci, $nombre, $email, $contrasena) {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO administradores (ci, nombre, email, contrasena, activo) 
                VALUES (?, ?, ?, ?, 1)";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("isss", $ci, $nombre, $email, $contrasena_hash);
        
        return $stmt->execute();
    }

    // ========== LISTAR ADMINS ==========
    public function listarTodos() {
        $sql = "SELECT id_admin, ci, nombre, email, activo, fecha_registro 
                FROM administradores 
                ORDER BY nombre ASC";
        
        $resultado = $this->conexion->query($sql);
        
        $admins = [];
        while ($fila = $resultado->fetch_assoc()) {
            $admins[] = $fila;
        }
        
        return $admins;
    }

    // ========== ACTIVAR/DESACTIVAR ADMIN ==========
    public function cambiarEstado($ci, $activo) {
        $sql = "UPDATE administradores SET activo = ? WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $activo, $ci);
        
        return $stmt->execute();
    }

    // ========== CAMBIAR CONTRASEÑA ==========
    public function cambiarContrasena($ci, $nueva_contrasena) {
        $contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        
        $sql = "UPDATE administradores SET contrasena = ? WHERE ci = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $contrasena_hash, $ci);
        
        return $stmt->execute();
    }
}
?>