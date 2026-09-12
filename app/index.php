<?php
session_start();

// ⛔ Sin sesión → ir a login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// ⛔ Solo ADMINISTRADOR y GERENTE pueden entrar aquí
$rolesPermitidos = ['ADMINISTRADOR', 'GERENTE'];
if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
    die("
    <h3>⚠️ Acceso Restringido</h3>
    <p>Esta área es exclusiva para Administradores y Gerentes.</p>
    <p><a href='../cliente/usuario.php'>Ir a mi cuenta</a> | 
       <a href='../logout.php'>Cerrar sesión</a></p>
    ");
}

// ✅ Solo personal autorizado
$nombre = htmlspecialchars($_SESSION['nombres'] ?? $_SESSION['nombre']);
$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Panel de Administración</h1>
    <p>Bienvenido <strong><?php echo $nombre; ?></strong> — Rol: <?php echo $rol; ?></p>
    <hr>
    <p>Aquí puedes gestionar todo el sistema: usuarios, productos, ventas, compras, etc.</p>
    <p><a href="../logout.php">Cerrar sesión</a></p>
</body>
</html>