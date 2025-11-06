<?php
require_once __DIR__ . '/../conexion.php';

class Socio {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    //  Crea un nuevo socio con su contraseña hasheada (encriptasda)
    public function crearSocio($ci, $nombre, $apellido, $correo, $contrasena) {
        $sql = "INSERT INTO socios (ci, nombre, apellido, correo, contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt->bind_param("sssss", $ci, $nombre, $apellido, $correo, $hash);
        return $stmt->execute();
    }
}
?>
