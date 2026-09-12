<?php
session_start();
require_once '../app/config.php';

// Obtener el ID del cliente usando la variable que realmente guardas en el login
$id_cliente = $_SESSION['cliente_id'] ?? $_SESSION['id_cliente'] ?? $_SESSION['sesion_id'] ?? null;
$email_cliente = $_SESSION['cliente_email'] ?? $_SESSION['sesion_email'] ?? $_SESSION['usuario'] ?? null;

if (!$id_cliente) {
    header("Location: login_cliente.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Normalizar datos recibidos a un arreglo estándar $items_a_agregar
    $items_a_agregar = [];

    // Caso A: Envío masivo de productos (Arreglo: productos[id_producto] = cantidad)
    if (isset($_POST['productos']) && is_array($_POST['productos'])) {
        foreach ($_POST['productos'] as $id_p => $cant) {
            $id_p = intval($id_p);
            $cant = intval($cant);
            if ($id_p > 0 && $cant > 0) {
                $items_a_agregar[$id_p] = $cant;
            }
        }
    } 
    // Caso B: Envío individual (Compatibilidad con el formulario actual)
    elseif (isset($_POST['id_producto'])) {
        $id_p = intval($_POST['id_producto']);
        $cant = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
        if ($id_p > 0 && $cant > 0) {
            $items_a_agregar[$id_p] = $cant;
        }
    }

    // Si hay productos válidos por procesar
    if (!empty($items_a_agregar)) {
        try {
            // 1. Obtener o crear la venta con estado 'ABIERTA' para el cliente
            $sql_venta = "SELECT id_venta, nro_venta FROM tb_ventas 
                          WHERE id_cliente = :id_cliente AND estado = 'ABIERTA' 
                          ORDER BY fyh_creacion DESC LIMIT 1";
            $stmt_venta = $pdo->prepare($sql_venta);
            $stmt_venta->execute([':id_cliente' => $id_cliente]);
            $venta = $stmt_venta->fetch(PDO::FETCH_ASSOC);

            if (!$venta) {
                // Crear nueva venta abierta
                $sql_nueva = "INSERT INTO tb_ventas (id_cliente, fyh_creacion, estado) 
                              VALUES (:id_cliente, NOW(), 'ABIERTA')";
                $stmt_nueva = $pdo->prepare($sql_nueva);
                $stmt_nueva->execute([':id_cliente' => $id_cliente]);
                
                $id_venta = $pdo->lastInsertId();
                $nro_venta = $id_venta; 

                // Asignar nro_venta
                $sql_update_nro = "UPDATE tb_ventas SET nro_venta = :nro_venta WHERE id_venta = :id_venta";
                $stmt_update_nro = $pdo->prepare($sql_update_nro);
                $stmt_update_nro->execute([
                    ':nro_venta' => $nro_venta,
                    ':id_venta'   => $id_venta
                ]);
            } else {
                $id_venta  = $venta['id_venta'];
                $nro_venta = $venta['nro_venta'];
            }

            // Preparar consultas fuera del bucle para mejor rendimiento
            $sql_check = "SELECT id_carrito, cantidad FROM tb_carrito 
                          WHERE id_venta = :id_venta AND id_producto = :id_producto";
            $stmt_check = $pdo->prepare($sql_check);

            $sql_update = "UPDATE tb_carrito SET cantidad = :cantidad WHERE id_carrito = :id_carrito";
            $stmt_update = $pdo->prepare($sql_update);

            $sql_insert = "INSERT INTO tb_carrito 
                          (id_venta, nro_venta, id_producto, cantidad, fyh_creacion) 
                          VALUES (:id_venta, :nro_venta, :id_producto, :cantidad, NOW())";
            $stmt_insert = $pdo->prepare($sql_insert);

            // 2. Recorrer y procesar cada producto seleccionado
            foreach ($items_a_agregar as $id_producto => $cantidad) {
                // Verificar si ya existe en la venta actual
                $stmt_check->execute([
                    ':id_venta'   => $id_venta,
                    ':id_producto' => $id_producto
                ]);
                $item_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if ($item_existente) {
                    // Sumar cantidad
                    $nueva_cantidad = $item_existente['cantidad'] + $cantidad;
                    $stmt_update->execute([
                        ':cantidad'   => $nueva_cantidad,
                        ':id_carrito' => $item_existente['id_carrito']
                    ]);
                } else {
                    // Insertar nuevo registro
                    $stmt_insert->execute([
                        ':id_venta'   => $id_venta,
                        ':nro_venta'  => $nro_venta,
                        ':id_producto' => $id_producto,
                        ':cantidad'   => $cantidad
                    ]);
                }
            }

            // Redirección
            if (isset($_POST['comprar_ahora'])) {
                header("Location: procesar_pago.php?id_venta=$id_venta");
            } else {
                header("Location: carrito.php?id_venta=$id_venta");
            }
            exit();

        } catch (PDOException $e) {
            die("Error al agregar productos: " . $e->getMessage());
        }
    }
}

header("Location: catalogo.php");
exit();