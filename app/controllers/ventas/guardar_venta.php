<?php
session_start();
include('../../config.php'); // ⚠️ Ajusta la ruta según tu ubicación

// Recibir datos
$nro_venta   = isset($_GET['nro_venta'])   ? trim($_GET['nro_venta'])   : '';
$id_producto = isset($_GET['id_producto']) ? trim($_GET['id_producto']) : '';
$cantidad    = isset($_GET['cantidad'])    ? trim($_GET['cantidad'])    : '';

// Validar campos
if(empty($nro_venta) || empty($id_producto) || empty($cantidad)){
    echo "<div class='alert alert-danger'>Faltan datos: nro_venta=$nro_venta, id_producto=$id_producto, cantidad=$cantidad</div>";
    exit;
}

try {
    // Verificar si ya está en el carrito
    $consulta = $pdo->prepare("SELECT * FROM tb_carrito 
                                WHERE nro_venta = :nro_venta AND id_producto = :id_producto");
    $consulta->bindValue(':nro_venta', $nro_venta);
    $consulta->bindValue(':id_producto', $id_producto);
    $consulta->execute();
    $producto_en_carrito = $consulta->fetch(PDO::FETCH_ASSOC);

    if($producto_en_carrito){
        // ✅ Ya existe → actualizar cantidad
        $nueva_cantidad = $producto_en_carrito['cantidad'] + $cantidad;
        $sentencia = $pdo->prepare("UPDATE tb_carrito SET cantidad = :cantidad 
                                     WHERE nro_venta = :nro_venta AND id_producto = :id_producto");
        $sentencia->bindValue(':cantidad', $nueva_cantidad);
        $sentencia->bindValue(':nro_venta', $nro_venta);
        $sentencia->bindValue(':id_producto', $id_producto);
        $sentencia->execute();
        echo "<div class='alert alert-success'>Cantidad actualizada correctamente</div>";
    } else {
        // ✅ No existe → insertar nuevo
        $sentencia = $pdo->prepare("INSERT INTO tb_carrito (nro_venta, id_producto, cantidad, fyh_creacion) 
                                     VALUES (:nro_venta, :id_producto, :cantidad, NOW())");
        $sentencia->bindValue(':nro_venta', $nro_venta);
        $sentencia->bindValue(':id_producto', $id_producto);
        $sentencia->bindValue(':cantidad', $cantidad);
        $sentencia->execute();
        echo "<div class='alert alert-success'>Producto agregado correctamente</div>";
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error en la base de datos: " . $e->getMessage() . "</div>";
}