<?php
session_start(); // ✅ Iniciar sesión al principio
include('../../config.php');

// Recibir todos los datos del formulario
$codigo = $_POST['codigo'];
$id_categoria = $_POST['id_categorias'];
$nombre = $_POST['nombre'];
$id_usuarios = $_POST['id_usuarios'];
$beneficios = $_POST['beneficios']; 

// === NUEVOS CAMPOS AGROINTERRA ===
$cantidad = $_POST['cantidad'] ?? '';
$unidad = $_POST['unidad'] ?? '';
$ingredientes = $_POST['ingredientes'] ?? '';
$propiedades = $_POST['propiedades'] ?? '';

$stock = $_POST['stock'];
$stock_minimo = $_POST['stock_minimo'];
$stock_maximo = $_POST['stock_maximo'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$fecha_ingreso = $_POST['fecha_ingreso'];
$id_producto = $_POST['id_producto'];
$fechaHora = date('Y-m-d H:i:s');


if (!empty($_FILES['image']['name'])) {
    // Se sube imagen nueva
    $nombreDelArchivo = date("Y-m-d-h-i-s");
    $image_text = $nombreDelArchivo . "_" . $_FILES['image']['name'];
    $location = "../../../almacen/img_productos/" . $image_text;
    move_uploaded_file($_FILES['image']['tmp_name'], $location);
} else {
    
    $image_text = $_POST['image_text'];
}

// ✅ CONSULTA AJUSTADA EXACTAMENTE A LOS CAMPOS DE TU TABLA
$sentencia = $pdo->prepare("UPDATE tb_almacen 
    SET codigo = :codigo,
        nombre = :nombre,
        beneficios = :beneficios,
        cantidad = :cantidad,
        unidad = :unidad,
        ingredientes = :ingredientes,
        propiedades = :propiedades,
        stock = :stock,
        stock_minimo = :stock_minimo,
        stock_maximo = :stock_maximo,
        precio_compra = :precio_compra,
        precio_venta = :precio_venta,
        fecha_ingreso = :fecha_ingreso,
        imagen = :imagen,
        id_usuarios = :id_usuarios,
        id_categoria = :id_categoria,
        fyh_actualizacion = :fyh_actualizacion

    WHERE id_producto = :id_producto
");

// ✅ ENLAZAR TODOS LOS PARÁMETROS
$sentencia->bindParam(':codigo', $codigo);
$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':beneficios', $beneficios);
$sentencia->bindParam(':cantidad', $cantidad);
$sentencia->bindParam(':unidad', $unidad);
$sentencia->bindParam(':ingredientes', $ingredientes);
$sentencia->bindParam(':propiedades', $propiedades);
$sentencia->bindParam(':stock', $stock);
$sentencia->bindParam(':stock_minimo', $stock_minimo);
$sentencia->bindParam(':stock_maximo', $stock_maximo);
$sentencia->bindParam(':precio_compra', $precio_compra);
$sentencia->bindParam(':precio_venta', $precio_venta);
$sentencia->bindParam(':fecha_ingreso', $fecha_ingreso);
$sentencia->bindParam(':imagen', $image_text);
$sentencia->bindParam(':id_usuarios', $id_usuarios);
$sentencia->bindParam(':id_categoria', $id_categoria);
$sentencia->bindParam(':fyh_actualizacion', $fechaHora);
$sentencia->bindParam(':id_producto', $id_producto);

// ✅ EJECUTAR Y RESPONDER
if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Se actualizó el producto correctamente";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/almacen/');
} else {
    $_SESSION['mensaje'] = "Error: no se pudo actualizar el producto";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/almacen/update.php?id=' . $id_producto);
}