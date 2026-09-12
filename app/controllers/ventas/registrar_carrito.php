<?php
// ✅ Corregida la ruta de inclusión (ajusta según tu estructura)
include('../../config.php');

// ✅ Recibir y VALIDAR todos los valores
$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
$cantidad    = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0; // 🔴 AQUÍ se convierte a número
$nro_venta   = isset($_POST['nro_venta']) ? intval($_POST['nro_venta']) : 0;

// ✅ Validaciones para evitar valores vacíos o incorrectos
if ($id_producto <= 0 || $cantidad <= 0 || $nro_venta <= 0) {
    echo "error_datos";
    exit;
}

// ✅ Preparar inserción
$sentencia = $pdo->prepare("INSERT INTO tb_carrito (id_producto, cantidad, nro_venta, fyh_creacion) 
                             VALUES (:id_producto, :cantidad, :nro_venta, :fyh_creacion)");

// ✅ Enlazar valores con tipo de dato INT
$sentencia->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
$sentencia->bindParam(':cantidad', $cantidad, PDO::PARAM_INT); // 🔴 AQUÍ SE GUARDA LA CANTIDAD REAL
$sentencia->bindParam(':nro_venta', $nro_venta, PDO::PARAM_INT);
$sentencia->bindParam(':fyh_creacion', $fechaHora);

if($sentencia->execute()){
    echo "correcto";
} else {
    echo "error";
}