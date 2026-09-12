<?php
session_start();
require_once '../app/config.php'; 

// 1. Obtener la sesión con las mismas claves que carrito.php
$id_cliente = $_SESSION['cliente_id'] ?? $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? 0;

// Si no hay sesión válida, regresar al carrito/login
if ($id_cliente === 0) {
    header("Location: carrito.php");
    exit();
}

// 2. Validar que el ID del carrito sea recibido por GET y sea numérico
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_carrito = (int)$_GET['id'];

    try {
        /*
         * 3. Eliminar el registro permitiendo items con id_venta = 0 / NULL 
         *    o asociados directamente al cliente autenticado
         */
        $sql = "DELETE c 
                FROM tb_carrito AS c
                LEFT JOIN tb_ventas AS v ON c.id_venta = v.id_venta
                WHERE c.id_carrito = :id_carrito 
                  AND (v.id_cliente = :id_cliente OR c.id_venta = 0 OR c.id_venta IS NULL)";

        $sentencia = $pdo->prepare($sql);
        $sentencia->execute([
            ':id_carrito' => $id_carrito,
            ':id_cliente' => $id_cliente
        ]);

        header("Location: carrito.php");
        exit();

    } catch (PDOException $e) {
        echo "Error al intentar eliminar el producto: " . htmlspecialchars($e->getMessage());
        exit();
    }
} else {
    header("Location: carrito.php");
    exit();
}

$id_cliente = $_SESSION['sesion_id'] ?? 0;

// 2. Validar que el ID del carrito sea recibido por GET y sea numérico
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_carrito = $_GET['id'];

    try {
        /* 
         * 3. Eliminar el registro de tb_carrito.
         * Se valida mediante JOIN con tb_ventas que el ítem pertenezca a la venta 
         * en estado 'ABIERTA' del cliente autenticado (evita que un usuario borre ítems ajenos).
         */
        $sql = "DELETE c 
                FROM tb_carrito AS c
                INNER JOIN tb_ventas AS v ON c.id_venta = v.id_venta
                WHERE c.id_carrito = :id_carrito 
                  AND v.id_cliente = :id_cliente 
                  AND v.estado = 'ABIERTA'";

        $sentencia = $pdo->prepare($sql);
        $sentencia->execute([
            ':id_carrito' => $id_carrito,
            ':id_cliente' => $id_cliente
        ]);

        // Redireccionar al carrito tras la eliminación exitosa
        header("Location: carrito.php");
        exit();

    } catch (PDOException $e) {
        // En caso de error en la base de datos
        echo "Error al intentar eliminar el producto: " . $e->getMessage();
        exit();
    }
} else {
    // Si no se proporcionó un ID válido, redirige al carrito
    header("Location: carrito.php");
    exit();
}