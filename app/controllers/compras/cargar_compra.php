<?php
// Validar que el id exista en la URL
if (isset($_GET['id'])) {
    $id_compra_get = $_GET['id'];

    $sql_compras = "SELECT *, 
        co.precio_compra as precio_compra, 
        co.cantidad as cantidad,
        pro.codigo as codigo, 
        pro.nombre as nombre_producto, 
        pro.cantidad as cantidad_producto,
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
        us_prod.nombres as nombre_usuarios_producto, 
        prov.nombre_proveedor as nombre_proveedor, 
        prov.celular as celular_proveedor, 
        prov.telefono as telefono_proveedor, 
        prov.empresa as empresa, 
        prov.email as email_proveedor, 
        prov.direccion as direccion_proveedor, 
        us.nombres as nombres_usuarios 
    FROM tb_compras as co 
    INNER JOIN tb_almacen as pro ON co.id_producto = pro.id_producto 
    INNER JOIN tb_categorias as cat ON cat.id_categoria = pro.id_categoria 
    INNER JOIN tb_usuarios as us ON co.id_usuarios = us.id_usuarios 
    LEFT JOIN tb_usuarios as us_prod ON pro.id_usuarios = us_prod.id_usuarios
    INNER JOIN tb_proveedores as prov ON co.id_proveedor = prov.id_proveedor 
    WHERE co.id_compra = '$id_compra_get'";

    $query_compras = $pdo->prepare($sql_compras);
    $query_compras->execute();
    $compras_datos = $query_compras->fetchAll(PDO::FETCH_ASSOC);

    $nro_compra = "";
    foreach ($compras_datos as $compras_dato){
        $id_compra = $compras_dato['id_compra'];
        $id_producto = $compras_dato['id_producto'];
        $id_proveedor_tabla = $compras_dato['id_proveedor'];
        $nro_compra = $compras_dato['nro_compra'];
        
        // Datos del Producto
        $codigo = $compras_dato['codigo'];
        $nombre_categoria = $compras_dato['nombre_categoria'];
        $nombre_producto = $compras_dato['nombre_producto'];
        $cantidad_producto = $compras_dato['cantidad_producto'];
        $unidad = $compras_dato['unidad'];
        $ingredientes = $compras_dato['ingredientes'];
        $propiedades = $compras_dato['propiedades'];
        $beneficios = $compras_dato['beneficios'];
        $stock = $compras_dato['stock'];
        $stock_minimo = $compras_dato['stock_minimo'];
        $stock_maximo = $compras_dato['stock_maximo'];
        $precio_compra_producto = $compras_dato['precio_compra_producto'];
        $precio_venta_producto = $compras_dato['precio_venta_producto'];
        $fecha_ingreso = $compras_dato['fecha_ingreso'];
        $imagen = $compras_dato['imagen'];
        $nombre_usuarios_producto = $compras_dato['nombre_usuarios_producto'];

        // Datos del Proveedor
        $nombre_proveedor_tabla = $compras_dato['nombre_proveedor'];
        $celular_proveedor = $compras_dato['celular_proveedor'];
        $telefono_proveedor = $compras_dato['telefono_proveedor'];
        $empresa = $compras_dato['empresa'];
        $email_proveedor = $compras_dato['email_proveedor'];
        $direccion_proveedor = $compras_dato['direccion_proveedor'];

        // Datos de la Compra
        $fecha_compra = $compras_dato['fecha_compra'];
        $cantidad = $compras_dato['cantidad'];
        $comprobante = $compras_dato['comprobante'];
        $precio_compra = $compras_dato['precio_compra'];
        $nombres_usuarios = $compras_dato['nombres_usuarios'];
    }
} else {
    $compras_datos = [];
    $nro_compra = "No especificado";
}