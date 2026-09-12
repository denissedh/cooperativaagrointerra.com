<?php
session_start();
require_once '../app/config.php';

// Validar que exista una sesión activa
if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.php");
    exit();
}

// Procesar solo si la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_SESSION['cliente_id'];

    try {
        // 1. Obtener y eliminar la foto de perfil en disco si existe
        $sentencia_foto = $pdo->prepare("SELECT foto_perfil FROM tb_clientes WHERE id_cliente = :id LIMIT 1");
        $sentencia_foto->execute([':id' => $id_cliente]);
        $cliente = $sentencia_foto->fetch(PDO::FETCH_ASSOC);

        if ($cliente && !empty($cliente['foto_perfil'])) {
            $ruta_archivo = '../uploads/perfil/' . $cliente['foto_perfil'];
            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        // 2. Eliminar el registro del cliente de la base de datos
        $sentencia_eliminar = $pdo->prepare("DELETE FROM tb_clientes WHERE id_cliente = :id");
        $sentencia_eliminar->execute([':id' => $id_cliente]);

        // 3. Limpiar variables de sesión y destruir la cookie
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        session_destroy();

        // 4. Redireccionar al usuario a la página principal
        header("Location: index.php?status=cuenta_eliminada");
        exit();

    } catch (PDOException $e) {
        // En caso de conflicto de clave foránea o error SQL
        header("Location: usuario.php?error=error_al_eliminar");
        exit();
    }
} else {
    // Si intentan entrar directo vía GET se redirige al perfil
    header("Location: usuario.php");
    exit();
}