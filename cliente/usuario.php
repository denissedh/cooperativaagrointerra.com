<?php
session_start();
require_once '../app/config.php'; // Conexión PDO: $pdo

$error = '';

// ======================================
// LÓGICA DE REGISTRO DE CLIENTE
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'registro') {
    $nombre_cliente    = trim($_POST['nombre_cliente'] ?? '');
    $email_cliente     = trim($_POST['email_cliente'] ?? '');
    $celular_cliente   = trim($_POST['celular_cliente'] ?? '');
    $direccion_cliente = trim($_POST['direccion_cliente'] ?? '');
    $password_cliente  = $_POST['password_cliente'] ?? '';

    if (empty($nombre_cliente) || empty($email_cliente) || empty($celular_cliente) || empty($password_cliente)) {
        $error = 'Por favor, llena todos los campos obligatorios.';
    } elseif (!preg_match('/[\W_]/', $password_cliente)) {
        $error = 'La contraseña debe incluir al menos un carácter especial (ej. @, #, $, !, %, *).';
    } else {
        $query_check = $pdo->prepare("SELECT id_cliente FROM tb_clientes WHERE email_cliente = :email LIMIT 1");
        $query_check->execute([':email' => $email_cliente]);

        if ($query_check->fetch()) {
            $error = 'El correo electrónico ya se encuentra registrado.';
        } else {
            $password_hashed = password_hash($password_cliente, PASSWORD_BCRYPT);
            $fecha_hora = date('Y-m-d H:i:s');

            $sentencia = $pdo->prepare("INSERT INTO tb_clientes 
                (nombre_cliente, email_cliente, celular_cliente, direccion_cliente, password_cliente, fyh_creacion) 
                VALUES (:nombre, :email, :celular, :direccion, :password, :fyh_creacion)");
            
            $resultado = $sentencia->execute([
                ':nombre'       => $nombre_cliente,
                ':email'        => $email_cliente,
                ':celular'      => $celular_cliente,
                ':direccion'    => $direccion_cliente,
                ':password'     => $password_hashed,
                ':fyh_creacion' => $fecha_hora
            ]);

            if ($resultado) {
                $_SESSION['cliente_email']  = $email_cliente;
                $_SESSION['cliente_id']     = $pdo->lastInsertId();
                $_SESSION['cliente_nombre'] = $nombre_cliente;
                $_SESSION['cliente_tipo']   = 'cliente';

                header("Location: index.php");
                exit();
            } else {
                $error = 'Error al registrar. Inténtalo de nuevo.';
            }
        }
    }
}

// ======================================
// VERIFICACIÓN DE SESIÓN DE CLIENTE
// ======================================
$es_cliente_logueado = isset($_SESSION['cliente_email']);
$foto_perfil = null;

if ($es_cliente_logueado) {
    $nombre_usuario = $_SESSION['cliente_nombre'] ?? 'ISABEL RUIZ';
    $id_cliente     = $_SESSION['cliente_id'] ?? 0;

    $sentencia = $pdo->prepare("SELECT foto_perfil FROM tb_clientes WHERE id_cliente = :id LIMIT 1");
    $sentencia->execute([':id' => $id_cliente]);
    $cliente_data = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($cliente_data && !empty($cliente_data['foto_perfil'])) {
        $foto_perfil = $cliente_data['foto_perfil'];
    }

    $correo_partes = explode('@', $_SESSION['cliente_email']);
    $username = '@' . ($correo_partes[0] ?? 'isarui01');
} else {
    // Por defecto para visualización / maquetación
    $nombre_usuario = 'ISABEL RUIZ';
    $username = '@isarui01';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — <?= $es_cliente_logueado ? 'Mi Perfil' : 'Crear Cuenta'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --brand-yellow: #EAA800;
            --brand-yellow-hover: #d69900;
            --text-dark: #222222;
            --bg-body: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* HEADER */
        .header-site {
            background: #FFFFFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 4rem 1.4rem 3.5rem;
            border-bottom: 2px solid var(--brand-yellow);
        }

        .logo-container a {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-container img {
            height: 52px;
            object-fit: contain;
        }

        .nav-principal {
            display: flex;
            align-items: center;
            gap: 2.8rem;
        }

        .nav-principal a {
            text-decoration: none;
            color: var(--brand-yellow);
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
            transition: opacity 0.2s ease;
        }

        .nav-principal a:hover {
            opacity: 0.8;
        }

        .user-pill {
            background-color: var(--brand-yellow);
            color: #FFFFFF !important;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-left: 0.5rem;
            transition: transform 0.2s ease;
        }

        .user-pill:hover {
            transform: scale(1.05);
            opacity: 1 !important;
        }

        /* LAYOUT PERFIL */
        .main-wrapper {
            max-width: 1300px;
            margin: 3.8rem auto 2rem auto;
            padding: 0 4rem;
            display: flex;
            align-items: flex-start;
            gap: 4.5rem;
            position: relative;
            z-index: 2;
        }

        /* SIDEBAR USUARIO */
        .profile-sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 240px;
            flex-shrink: 0;
        }

        .avatar-circle {
            width: 220px;
            height: 220px;
            background-color: var(--brand-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #FFFFFF;
        }

        .avatar-circle svg {
            width: 58%;
            height: 58%;
            fill: #FFFFFF;
        }

        .profile-links {
            margin-top: 3.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            width: 100%;
            align-items: center;
        }

        .profile-links a {
            color: #1a1a1a;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: color 0.2s;
        }

        .profile-links a:hover {
            color: var(--brand-yellow);
        }

        .profile-links a i {
            font-size: 1.15rem;
            color: #1a1a1a;
        }

        /* BOTÓN DE ELIMINAR CUENTA */
        .btn-delete-profile {
            background: none;
            border: none;
            color: #d9534f;
            font-size: 0.88rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            font-family: inherit;
            padding: 0;
            transition: color 0.2s;
        }

        .btn-delete-profile:hover {
            color: #b52b27;
        }

        .btn-delete-profile i {
            font-size: 1.15rem;
            color: inherit;
        }

        /* PANEL CENTRAL */
        .profile-content {
            flex: 1;
            max-width: 780px;
        }

        .user-name-title {
            font-weight: 800;
            font-size: 2.7rem;
            color: var(--brand-yellow);
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin-bottom: 0.1rem;
        }

        .user-tag {
            font-size: 1.2rem;
            color: var(--brand-yellow);
            font-weight: 500;
            margin-bottom: 2.2rem;
        }

        /* BOTONES / TARJETAS */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.8rem 1.6rem;
        }

        .action-card {
            background-color: var(--brand-yellow);
            border-radius: 40px;
            padding: 1.9rem 2.2rem;
            min-height: 142px;
            text-decoration: none;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            gap: 0.45rem;
            transition: transform 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .action-card:hover {
            background-color: var(--brand-yellow-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(234, 168, 0, 0.22);
        }

        .action-card i {
            font-size: 1.9rem;
            line-height: 1;
        }

        .action-card span {
            font-weight: 500;
            font-size: 1.12rem;
            letter-spacing: 0.2px;
        }

        /* FORMULARIO REGISTRO (SI NO HAY SESIÓN) */
        .register-box {
            max-width: 480px;
            margin: 3.5rem auto;
            padding: 2.5rem;
            background: #FFFFFF;
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border-top: 6px solid var(--brand-yellow);
        }

        .register-title {
            color: var(--brand-yellow);
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .alert-box {
            background-color: #FDF2F2;
            color: #9B1C1C;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 0.4rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1.1rem;
            border: 2px solid #EAEAEA;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: var(--brand-yellow);
        }

        /* CONTENEDOR TOGGLE PASSWORD */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 3rem;
        }

        .btn-toggle-password {
            position: absolute;
            right: 1.1rem;
            background: none;
            border: none;
            color: #888;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: color 0.2s;
        }

        .btn-toggle-password:hover {
            color: var(--brand-yellow);
        }

        .btn-submit {
            width: 100%;
            background: var(--brand-yellow);
            color: #FFFFFF;
            border: none;
            padding: 1rem;
            border-radius: 16px;
            font-family: inherit;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--brand-yellow-hover);
        }

        @media (max-width: 950px) {
            .header-site {
                padding: 1.2rem 1.5rem;
            }
            .nav-principal {
                gap: 1.4rem;
            }
            .main-wrapper {
                flex-direction: column;
                align-items: center;
                gap: 2.5rem;
                padding: 0 1.5rem;
            }
            .profile-sidebar {
                align-items: center;
            }
            .profile-links {
                margin-top: 1.5rem;
            }
            .profile-content {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 600px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .nav-principal a:not(.user-pill) {
                display: none;
            }
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="../cliente/img_clientes/image.png" alt="COOPERATIVA AGROINTERRA" onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
            <div id="logo-fallback" style="display:none; align-items:center; gap:10px;">
                <svg width="42" height="42" viewBox="0 0 100 100" fill="#EAA800">
                    <circle cx="50" cy="20" r="10"/>
                    <circle cx="50" cy="80" r="10"/>
                    <circle cx="20" cy="50" r="10"/>
                    <circle cx="80" cy="50" r="10"/>
                    <circle cx="28" cy="28" r="8"/>
                    <circle cx="72" cy="72" r="8"/>
                    <circle cx="28" cy="72" r="8"/>
                    <circle cx="72" cy="28" r="8"/>
                </svg>
                <div style="color:#EAA800; font-weight:800; font-size:1.1rem; line-height:1.05; letter-spacing:0.5px;">
                    COOPERATIVA<br>AGROINTERRA
                </div>
            </div>
        </a>
    </div>

    <nav class="nav-principal">
        <a href="index.php">INICIO</a>
        <a href="catalogo.php">CATÁLOGO</a>
        <a href="quienes_somos.php">¿QUIÉNES SOMOS?</a>
        <a href="contacto.php">CONTÁCTO</a>
        <a href="usuario.php" class="user-pill"><i class="bi bi-person-fill"></i></a>
    </nav>
</header>

<main>
    <?php if ($es_cliente_logueado): ?>
        <div class="main-wrapper">
            <div class="profile-sidebar">
                <div class="avatar-circle">
                    <?php if ($foto_perfil && file_exists('../uploads/perfil/' . $foto_perfil)): ?>
                        <img src="../uploads/perfil/<?= htmlspecialchars($foto_perfil) ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4.2"/>
                            <path d="M12 13.5c-4.4 0-8 2.2-8 5v1.5h16v-1.5c0-2.8-3.6-5-8-5z"/>
                        </svg>
                    <?php endif; ?>
                </div>
                
                <div class="profile-links">
                    <a href="editar_perfil.php">
                        <i class="bi bi-gear-fill"></i>
                        Editar perfil
                    </a>
                    <a href="cerrar_sesion.php">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar sesión
                    </a>
                    
                    <form action="eliminar_perfil.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta permanentemente? Esta acción borrará tus datos y no se puede deshacer.');">
                        <button type="submit" class="btn-delete-profile">
                            <i class="bi bi-trash3-fill"></i>
                            Eliminar cuenta
                        </button>
                    </form>
                </div>
            </div>

            <div class="profile-content">
                <h1 class="user-name-title"><?= htmlspecialchars($nombre_usuario) ?></h1>
                <p class="user-tag"><?= htmlspecialchars($username) ?></p>

                <div class="cards-grid">
                    <a href="carrito.php" class="action-card">
                        <i class="bi bi-cart2"></i>
                        <span>Mis productos</span>
                    </a>
                    <a href="facturacion.php" class="action-card">
                        <i class="bi bi-receipt"></i>
                        <span>Facturación</span>
                    </a>
                    <a href="notificaciones.php" class="action-card">
                        <i class="bi bi-bell"></i>
                        <span>Notificaciónes</span>
                    </a>
                    <a href="chat.php" class="action-card">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Chat</span>
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="register-box">
            <h1 class="register-title">Crear Cuenta</h1>
            <?php if ($error): ?>
                <div class="alert-box"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <input type="hidden" name="action" value="registro">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre_cliente" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email_cliente" required>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion_cliente" required>
                </div>
                <div class="form-group">
                    <label>Número de Celular</label>
                    <input type="tel" name="celular_cliente" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_cliente" id="password_cliente" placeholder="Mínimo 6 caracteres" required>
                        <button type="button" class="btn-toggle-password" id="togglePasswordBtn" aria-label="Mostrar u ocultar contraseña">
                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Registrarse</button>
            </form>
            <p style="text-align: center; margin-top: 1.2rem; font-size: 0.9rem;">
                ¿Ya tienes cuenta? <a href="login_cliente.php" style="color: var(--brand-yellow); font-weight: 700; text-decoration: none;">Inicia sesión aquí</a>
            </p>
        </div>
    <?php endif; ?>
</main>

<script>
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password_cliente');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            
            toggleIcon.classList.toggle('bi-eye-slash', !isPassword);
            toggleIcon.classList.toggle('bi-eye', isPassword);
        });
    }
</script>

</body>
</html>