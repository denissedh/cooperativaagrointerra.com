<?php
session_start();
include('../app/config.php');

$error = "";

// Si ya tiene sesión, redirigir directamente
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'ADMINISTRADOR' || $_SESSION['rol'] === 'GERENTE') {
        header("Location: ../app/index.php");
    } else {
        header("Location: ../cliente/index.php");
    }
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['email'] ?? '');
    $clave  = $_POST['password'] ?? '';

    if (empty($correo) || empty($clave)) {
        $error = "Completa todos los campos.";
    } else {
        // ✅ Consulta adaptada a tus tablas
        $stmt = $pdo->prepare("
            SELECT u.id_usuarios, u.nombres, u.email, u.password_user, r.rol
            FROM tb_usuarios u
            INNER JOIN tb_roles r ON u.id_rol = r.id_rol
            WHERE u.email = ? LIMIT 1
        ");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($clave, $usuario['password_user'])) {
            // ✅ Crear sesión segura
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id_usuarios'];
            $_SESSION['nombre']     = $usuario['nombres'];
            $_SESSION['email']      = $usuario['email'];
            $_SESSION['rol']        = $usuario['rol'];

            // 🚀 Redirigir según el rol
            if ($usuario['rol'] === 'ADMINISTRADOR' || $usuario['rol'] === 'GERENTE') {
                header("Location: ../app/index.php");
            } else {
                header("Location: ../cliente/index.php");
            }
            exit;
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <p>
            <label>Correo electrónico:</label><br>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <button type="submit">Entrar</button>
        </p>
        <p>
            ¿No tienes cuenta? <a href="../cliente/registro_cliente.php">Regístrate aquí</a>
        </p>
    </form>
</body>
</html>