<?php
// 1. Iniciar o reanudar la sesión existente
session_start();

// 2. Limpiar/vaciar todas las variables de sesión
$_SESSION = array();

// 3. Destruir la cookie de sesión en el navegador (opcional pero recomendado por seguridad)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir totalmente la sesión activa
session_destroy();

// 5. Redirigir al usuario a la página principal (index.php)
header("Location: ../cliente/index.php");
exit();
?>