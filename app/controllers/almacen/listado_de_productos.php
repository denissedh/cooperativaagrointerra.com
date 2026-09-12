<?php
$sql_productos = "SELECT *, cat.nombre_categoria as categoria, u.email as email, a.cantidad, a.unidad, a.ingredientes, a.propiedades
                  FROM tb_almacen as a 
                  INNER JOIN tb_categorias as cat ON a.id_categoria = cat.id_categoria 
                  INNER JOIN tb_usuarios as u ON u.id_usuarios = a.id_usuarios";
$query_productos = $pdo->prepare($sql_productos);
$query_productos->execute();
$productos_datos = $query_productos->fetchAll(PDO::FETCH_ASSOC);