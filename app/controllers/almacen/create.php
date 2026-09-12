<?php
include('../../config.php');
// Recibir datos — CORREGIDO: id_categoria, beneficios
$codigo = $_POST['codigo'] ?? '';
$id_categoria = $_POST['id_categoria'] ?? ''; 
$nombre = $_POST['nombre'] ?? '';
$id_usuarios = $_POST['id_usuarios'] ?? '';
$beneficios = $_POST['beneficios'] ?? ''; 

// === NUEVOS CAMPOS AGROINTERRA ===
$cantidad = $_POST['cantidad'] ?? '';
$unidad = $_POST['unidad'] ?? '';
$ingredientes = $_POST['ingredientes'] ?? '';
$propiedades = $_POST['propiedades'] ?? '';
$stock = $_POST['stock'] ?? 0;
$stock_minimo = $_POST['stock_minimo'] ?? 0;
$stock_maximo = $_POST['stock_maximo'] ?? 0;
$precio_compra = $_POST['precio_compra'] ?? ''; 
$precio_venta = $_POST['precio_venta'] ?? '';   
$fecha_ingreso = $_POST['fecha_ingreso'] ?? null;

$fechaHora = date('Y-m-d H:i:s'); 
$filename = "";


if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0 && $_FILES['imagen']['name'] != "") {

    $directorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/www.sistemadeventas.com/almacen/img_productos/';
    
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }
    
    $nombreBase = pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME);
    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreLimpio = preg_replace('/[^a-zA-Z0-9]/', '_', $nombreBase);
    
    $filename = date("YmdHis") . "_" . $nombreLimpio . "." . $extension;
    $location = $directorioDestino . $filename;
            
    if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $location)){
        die("Error al mover el archivo. Verifica permisos de carpeta.");
    }
}

$sentencia = $pdo->prepare("INSERT INTO tb_almacen 
(codigo, id_categoria, nombre, id_usuarios, beneficios, cantidad, unidad, ingredientes, propiedades, stock, stock_minimo, stock_maximo, precio_compra, precio_venta, fecha_ingreso, imagen, fyh_creacion, fyh_actualizacion) 
VALUES (:codigo, :id_categoria, :nombre, :id_usuarios, :beneficios, :cantidad, :unidad, :ingredientes, :propiedades, :stock, :stock_minimo, :stock_maximo, :precio_compra, :precio_venta, :fecha_ingreso, :imagen, :fyh_creacion, :fyh_actualizacion)");

$sentencia->bindParam(':codigo', $codigo);
$sentencia->bindParam(':id_categoria', $id_categoria); 
$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':id_usuarios', $id_usuarios);
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
$sentencia->bindParam(':imagen', $filename);
$sentencia->bindParam(':fyh_creacion', $fechaHora);
$sentencia->bindParam(':fyh_actualizacion', $fechaHora); 

session_start();
if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Producto registrado correctamente";
    header('Location: ' . $URL . '/almacen/');
} else {
    $_SESSION['mensaje'] = "Error: no se pudo registrar el producto";
    header('Location: ' . $URL . '/almacen/create.php');
}
?>