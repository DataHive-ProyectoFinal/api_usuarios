<?php
require_once(__DIR__ . "/../modelos/pago_inicial.php");
require_once(__DIR__ . "/../conexion.php");

$pago = new Pago($conexion);

$ci = $_POST['ci'];
$monto = $_POST['monto'];

if (!empty($_FILES['comprobante']['name'])) {
    $dir = __DIR__ . "/../uploads/pagosInicial/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $nombreArchivo = time() . "_" . basename($_FILES['comprobante']['name']);
    $ruta = $dir . $nombreArchivo;

    if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta)) {
        if ($pago->crear($ci, $monto, $nombreArchivo)) {
            echo json_encode(["status" => "ok", "mensaje" => "Pago enviado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error al guardar en la base de datos."]);
        }
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Error al subir el archivo."]);
    }
}
?>
