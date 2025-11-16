<?php
// api_usuarios/subir_pago_inicial.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'socio') {
        throw new Exception("Debe iniciar sesión como socio.");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    require_once __DIR__ . '/../shared/modelo/PagoModel.php';
    require_once __DIR__ . '/config/conexion.php';

    $ci_socio = $_SESSION['ci'];
    $monto = $_POST['monto'] ?? null;
    $fecha_pago = $_POST['fecha_pago'] ?? null;
    $descripcion = $_POST['descripcion'] ?? '';

    // Validaciones
    if (empty($monto) || empty($fecha_pago)) {
        throw new Exception("Debe completar todos los campos obligatorios.");
    }

    // Verificar que no haya subido ya un pago inicial
    $pagoModel = new PagoModel($conexion);
    
    if ($pagoModel->tienePagoInicialAprobado($ci_socio)) {
        throw new Exception("Ya tiene un pago inicial aprobado.");
    }
    
    if ($pagoModel->tienePagoInicialPendiente($ci_socio)) {
        throw new Exception("Ya tiene un pago inicial pendiente de aprobación.");
    }

    // Manejo del archivo
    if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Debe subir un comprobante.");
    }

    $archivo = $_FILES['comprobante'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!in_array($extension, $extensiones_permitidas)) {
        throw new Exception("Formato de archivo no permitido. Use JPG, PNG o PDF.");
    }

    if ($archivo['size'] > 5 * 1024 * 1024) {
        throw new Exception("El archivo es demasiado grande. Máximo 5MB.");
    }

    // Generar nombre único para el archivo
    $nombre_archivo = 'pago_inicial_' . $ci_socio . '_' . time() . '.' . $extension;
    $ruta_uploads = __DIR__ . '/../uploads/comprobantes/';
    
    // Crear directorio si no existe
    if (!is_dir($ruta_uploads)) {
        mkdir($ruta_uploads, 0777, true);
    }
    
    $ruta_destino = $ruta_uploads . $nombre_archivo;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        throw new Exception("Error al subir el archivo.");
    }

    // Crear pago inicial
    $id_pago = $pagoModel->crear($ci_socio, 'inicial', $monto, $fecha_pago, $nombre_archivo, $descripcion);

    echo json_encode([
        'success' => true,
        'message' => 'Pago inicial subido correctamente. Será revisado por un administrador.',
        'id_pago' => $id_pago
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>