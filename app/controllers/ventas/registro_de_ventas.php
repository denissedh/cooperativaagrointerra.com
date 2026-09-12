<?php
include ('../../config.php');

$nro_venta = $_GET['nro_venta'];
$id_cliente = $_GET['id_cliente'];
$total_a_pagar = $_GET['total_a_pagar'];

$pdo->beginTransaction();

// Insertamos también el estado como COMPLETADA
$sentencia = $pdo->prepare("INSERT INTO tb_ventas
       (nro_venta, id_cliente, total_pagado, estado, fyh_creacion) 
VALUES (:nro_venta, :id_cliente, :total_pagado, :estado, :fyh_creacion)");

$estado = "COMPLETADA";

$sentencia->bindParam(':nro_venta', $nro_venta);
$sentencia->bindParam(':id_cliente', $id_cliente);
$sentencia->bindParam(':total_pagado', $total_a_pagar);
$sentencia->bindParam(':estado', $estado);
$sentencia->bindParam(':fyh_creacion', $fechaHora);

if($sentencia->execute()){

    $pdo->commit();

    session_start();
//    $_SESSION['mensaje'] = "Venta Registrada con éxito";
    $_SESSION['icono'] = "success";
} else {
    $pdo->rollBack();
    echo "error";
}