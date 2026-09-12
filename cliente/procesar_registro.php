<?php
// Incluir la conexión PDO y configuración global
include ('../app/config.php');

session_start();

// Recibir campos del formulario
$nombre_cliente  = trim($_POST['nombre_cliente']);
$nit_ci_cliente  = trim($_POST['nit_ci_cliente']);
$celular_cliente = trim($_POST['celular_cliente']);
$email_cliente   = trim($_POST['email_cliente']);
$password        = $_POST['password'];
$confirm_pass    = $_POST['confirm_password'];

// 1. Verificar si el correo ya existe
$query_check = $pdo->prepare("SELECT * FROM tb_clientes WHERE email_cliente = :email LIMIT 1");
$query_check->bindParam(':email', $email_cliente);
$query_check->execute();
$cliente_existente = $query_check->fetch(PDO::FETCH_ASSOC);

if ($cliente_existente) {
    if (password_verify($password, $cliente_existente['password_cliente'])) {
        $_SESSION['id_cliente']     = $cliente_existente['id_cliente'];
        $_SESSION['nombre_cliente'] = $cliente_existente['nombre_cliente'];
        $_SESSION['email_cliente']  = $cliente_existente['email_cliente'];

        $_SESSION['mensaje'] = "¡Bienvenido de nuevo, " . $cliente_existente['nombre_cliente'] . "!";
        $_SESSION['icono']   = "success";

        header("Location: " . $URL . "/index.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "El correo ya está registrado. La contraseña ingresada es incorrecta.";
        $_SESSION['icono']   = "error";

        header("Location: " . $URL . "/registro.php");
        exit();
    }
}

// 2. Validar coincidencia de contraseñas
if ($password !== $confirm_pass) {
    $_SESSION['mensaje'] = "Las contraseñas ingresadas no coinciden.";
    $_SESSION['icono']   = "warning";

    header("Location: " . $URL . "/registro.php");
    exit();
}

// 3. Encriptar contraseña
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// 4. Insertar en tb_clientes
$sentencia = $pdo->prepare("INSERT INTO tb_clientes 
    (nombre_cliente, nit_ci_cliente, celular_cliente, email_cliente, password_cliente, fyh_creacion) 
VALUES 
    (:nombre, :nit_ci, :celular, :email, :password, :fyh_creacion)");

$sentencia->bindParam(':nombre', $nombre_cliente);
$sentencia->bindParam(':nit_ci', $nit_ci_cliente);
$sentencia->bindParam(':celular', $celular_cliente);
$sentencia->bindParam(':email', $email_cliente);
$sentencia->bindParam(':password', $password_hashed);
$sentencia->bindParam(':fyh_creacion', $fechaHora);

if ($sentencia->execute()) {
    $id_nuevo = $pdo->lastInsertId();

    $_SESSION['id_cliente']     = $id_nuevo;
    $_SESSION['nombre_cliente'] = $nombre_cliente;
    $_SESSION['email_cliente']  = $email_cliente;

    $_SESSION['mensaje'] = "Cuenta creada e inicio de sesión exitoso.";
    $_SESSION['icono']   = "success";

    header("Location: " . $URL . "/index.php");
    exit();
} else {
    $_SESSION['mensaje'] = "Error: No se pudo registrar en la base de datos.";
    $_SESSION['icono']   = "error";

    header("Location: " . $URL . "/registro.php");
    exit();
}