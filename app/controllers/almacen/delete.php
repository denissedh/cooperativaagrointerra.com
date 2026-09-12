<?php
include ('../../config.php');

$id_producto = $_POST['id_producto'];

// PASO 1: Obtener la imagen antes de borrar el registro
$sql_imagen = "SELECT imagen FROM tb_almacen WHERE id_producto = :id_producto";
$consulta_imagen = $pdo->prepare($sql_imagen);
$consulta_imagen->bindParam(':id_producto', $id_producto);
$consulta_imagen->execute();
$producto = $consulta_imagen->fetch(PDO::FETCH_ASSOC);

// PASO 2: Eliminar el archivo de imagen si existe
if (!empty($producto['imagen'])) {
    $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/www.sistemadeventas.com/almacen/img_productos/' . $producto['imagen'];
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen); // Borra físicamente la imagen
    }
}

// PASO 3: Eliminar el registro de la base de datos
$sentencia = $pdo->prepare("DELETE FROM tb_almacen WHERE id_producto = :id_producto");
$sentencia->bindParam(':id_producto', $id_producto);

session_start();
if($sentencia->execute()){
    $_SESSION['mensaje'] = "Se eliminó el producto correctamente";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/almacen/');
}else{
    $_SESSION['mensaje'] = "Error: no se pudo eliminar el producto";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/almacen/delete.php?id='.$id_producto);
}