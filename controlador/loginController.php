<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
session_start();
require_once(__DIR__ . "/../conexion.php"); 
require_once(__DIR__ . "/../modelo/login/usuario.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ci = $_POST["ci_usuario"] ?? "";
    $password = $_POST["password"] ?? "";

    $usuario = new Usuario($conexion);
    $resultado = $usuario->login($ci, $password);

    if ($resultado["success"]) {

        // Guardamos en sesión la CI y el rol
        $_SESSION["ci_usuario"] = $ci;
        $_SESSION["rol"] = $resultado["rol"];

        // Verificamos si es socio
        if ($resultado["rol"] === "socio") {
            $perfilCompleto = $resultado["perfil_completo"] ?? 0;

            if ($perfilCompleto == 0) {
                // Primer inicio: redirige al formulario para completar perfil
                echo json_encode([
                    "success" => true,
                    "msg" => "Primer ingreso, debe completar su perfil",
                    "ci" => $ci,
                    "rol" => "socio",
                    "redirect" => "../../Frontend/vista/Socios/completar_perfil.html"
                ]);
                exit;
            } else {
                // sino perfil ya completo  redirige al home de socios
                echo json_encode([
                    "success" => true,
                    "msg" => "Login exitoso",
                    "ci" => $ci,
                    "rol" => "socio",
                    "redirect" => "../../Frontend/vista/Socios/home-frontend-socios.html"
                ]);
                exit;
            }
        }

        // Si es admin  redirige al backoffice
        if ($resultado["rol"] === "admin") {
            echo json_encode([
                "success" => true,
                "msg" => "Login exitoso",
                "ci" => $ci,
                "rol" => "admin",
                "redirect" => "../../Frontend/vista/Backoffice/home-backoffice.html"
            ]);
            exit;
        }

    } else {
        echo json_encode([
            "success" => false,
            "msg" => "Credenciales incorrectas"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "msg" => "Método no permitido"
    ]);
}
?>
