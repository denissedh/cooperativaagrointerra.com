<?php
$id_producto_get = $_GET['id'];

$sql_productos = "SELECT a.*, 
                         cat.nombre_categoria as categoria, 
                         u.email as email, 
                         u.id_usuarios as id_usuarios
                  FROM tb_almacen as a 
                  INNER JOIN tb_categorias as cat ON a.id_categoria = cat.id_categoria 
                  INNER JOIN tb_usuarios as u ON u.id_usuarios = a.id_usuarios 
                  WHERE a.id_producto = :id_producto";

$query_productos = $pdo->prepare($sql_productos);
$query_productos->bindParam(':id_producto', $id_producto_get);
$query_productos->execute();
$productos_datos = $query_productos->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos_datos as $productos_dato){
    // Identificadores y relaciones
    $id_producto = $productos_dato['id_producto'];
    $id_categoria = $productos_dato['id_categoria'];
    $nombre_categoria = $productos_dato['categoria'] ?? $productos_dato['nombre_categoria'];
    $id_usuarios = $productos_dato['id_usuarios'];
    $email = $productos_dato['email'];

    // Datos principales del producto
    $codigo = $productos_dato['codigo'];
    $nombre = $productos_dato['nombre'];
    $beneficios = $productos_dato['beneficios'] ?? ''; // Reemplaza a descripcion

    // Inventario y precios
    $stock = $productos_dato['stock'];
    $stock_minimo = $productos_dato['stock_minimo'];
    $stock_maximo = $productos_dato['stock_maximo'];
    $precio_compra = $productos_dato['precio_compra'];
    $precio_venta = $productos_dato['precio_venta'];
    
    // Fechas y multimedia
    $fecha_ingreso = $productos_dato['fecha_ingreso'];
    $imagen = $productos_dato['imagen'];
    $fyh_creacion = $productos_dato['fyh_creacion'];
    $fyh_actualizacion = $productos_dato['fyh_actualizacion'];
    
    // Campos Agrointerra
    $cantidad = $productos_dato['cantidad'] ?? '';
    $unidad = $productos_dato['unidad'] ?? '';
    $ingredientes = $productos_dato['ingredientes'] ?? '';
    $propiedades = $productos_dato['propiedades'] ?? '';
}