<?php
require_once(__DIR__ . "/../../conexion.php");

class Usuario {
    private $conexion;

    public function __construct($conexion) {
            $this->conexion = $conexion;
        }

        public function login($ci, $password) {
        // Buscar en socios
        $stmt = $this->conexion->prepare("SELECT ci, contrasena, perfil_completo FROM socios WHERE ci = ?");
        if (!$stmt) {
            return ["success" => false, "msg" => "Error SQL socios: " . $this->conexion->error];
        }

        $stmt->bind_param("s", $ci);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $fila = $result->fetch_assoc();

            // Verificamos la contraseña hasheada
            if (password_verify($password, $fila["contrasena"])) {
                return [
                    "success" => true,
                    "rol" => "socio",
                    "ci" => $ci,
                    "perfil_completo" => (int)$fila["perfil_completo"]
                ];
            }
        }

        // Buscar en admins
        $stmt = $this->conexion->prepare("SELECT ci, contrasena FROM admins WHERE ci = ?");
        if (!$stmt) {
            return ["success" => false, "msg" => "Error SQL admins: " . $this->conexion->error];
        }

        $stmt->bind_param("s", $ci);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $fila = $result->fetch_assoc();

            if (password_verify($password, $fila["contrasena"])) {
                return ["success" => true, "rol" => "admin", "ci" => $ci];
            }
        }

        return ["success" => false, "msg" => "Credenciales incorrectas"];
    }

}
