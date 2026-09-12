<?php

$sql_compras = "SELECT *,
    co.precio_compra as precio_compra,
    co.cantidad as cantidad_compra,
    pro.codigo as codigo, 
    pro.nombre as nombre_producto, 
    pro.cantidad as cantidad,
    pro.unidad as unidad,
    pro.ingredientes as ingredientes,
    pro.propiedades as propiedades,
    pro.beneficios as beneficios, 
    pro.stock as stock,
    pro.stock_minimo as stock_minimo, 
    pro.stock_maximo as stock_maximo, 
    pro.precio_compra as precio_compra_producto,
    pro.precio_venta as precio_venta_producto, 
    pro.fecha_ingreso as fecha_ingreso, 
    pro.imagen as imagen,
    cat.nombre_categoria as nombre_categoria,
    prov.nombre_proveedor as nombre_proveedor, 
    prov.celular as celular_proveedor, 
    prov.telefono as telefono_proveedor,
    prov.empresa as empresa, 
    prov.email as email_proveedor, 
    prov.direccion as direccion_proveedor, 
    us.nombres as nombres_usuario
FROM tb_compras as co 
INNER JOIN tb_almacen as pro ON co.id_producto = pro.id_producto 
INNER JOIN tb_categorias as cat ON cat.id_categoria = pro.id_categoria
INNER JOIN tb_usuarios as us ON co.id_usuarios = us.id_usuarios 
INNER JOIN tb_proveedores as prov ON co.id_proveedor = prov.id_proveedor";

$query_compras = $pdo->prepare($sql_compras);
$query_compras->execute();
$compras_datos = $query_compras->fetchAll(PDO::FETCH_ASSOC);

$contador_de_compras = count($compras_datos) + 1;