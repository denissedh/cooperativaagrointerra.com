<?php
session_start();
include('../app/config.php');
$error = "";
$exito = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = trim($_POST['nombres'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $clave   = $_POST['password'] ?? '';

    if (empty($nombres) || empty($email) || empty($clave)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (strlen($clave) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id_usuarios FROM tb_usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Este correo ya está registrado.";
        } else {
            // ✅ Cliente siempre = rol CLIENTE (no se deja elegir)
            $password_hash = password_hash($clave, PASSWORD_DEFAULT);
            
            // Buscar id_rol de CLIENTE
            $stmtRol = $pdo->prepare("SELECT id_rol FROM tb_roles WHERE rol = 'CLIENTE' LIMIT 1");
            $stmtRol->execute();
            $rolCliente = $stmtRol->fetch();
            $id_rol = $rolCliente['id_rol'] ?? 3; // Ajusta el número si tu CLIENTE tiene otro id

            $stmt = $pdo->prepare("
                INSERT INTO tb_usuarios (nombres, email, password_user, id_rol, fyh_creacion)
                VALUES (?, ?, ?, ?, NOW())
            ");
            if ($stmt->execute([$nombres, $email, $password_hash, $id_rol])) {
                $exito = "¡Registro exitoso! Ya puedes <a href='../login/login.php'>iniciar sesión</a>.";
            } else {
                $error = "Error al registrar la cuenta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
</head>
<body>
    <h2>Crear Cuenta de Cliente</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($exito): ?>
        <p style="color:green;"><?php echo $exito ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <p>
            <label>Nombre completo:</label><br>
            <input type="text" name="nombres" required>
        </p>
        <p>
            <label>Correo electrónico:</label><br>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <button type="submit">Registrarme</button>
        </p>
        <p>
            ¿Ya tienes cuenta? <a href="../login/login.php">Iniciar sesión</a>
        </p>
    </form>
</body>
</html>