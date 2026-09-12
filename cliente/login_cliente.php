<?php
session_start();
require_once '../app/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email_cliente'] ?? '');
    $pass  = $_POST['password_cliente'] ?? '';

    if (empty($email) || empty($pass)) {
        $error = 'Completa todos los campos.';
    } else {
        // Buscar cliente en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM tb_clientes WHERE email_cliente = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($cliente && password_verify($pass, $cliente['password_cliente'])) {
            // ✅ INICIAR SESIÓN SOLO COMO CLIENTE
            $_SESSION['cliente_email']  = $cliente['email_cliente'];
            $_SESSION['cliente_id']     = $cliente['id_cliente'];
            $_SESSION['cliente_nombre'] = $cliente['nombre_cliente'];
            $_SESSION['cliente_tipo']   = 'cliente';

            // ✅ REDIRIGIR AL INDEX DEL CLIENTE
            header("Location: index.php");
            exit();
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Iniciar Sesión</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
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

        /* TARJETA DE LOGIN */
        .login-box {
            max-width: 460px;
            margin: 4.5rem auto 3rem auto;
            padding: 2.8rem 2.5rem;
            background: #FFFFFF;
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border-top: 6px solid var(--brand-yellow);
        }

        .login-icon {
            text-align: center;
            font-size: 3rem;
            color: var(--brand-yellow);
            margin-bottom: 0.5rem;
        }

        .login-title {
            color: var(--brand-yellow);
            font-size: 1.85rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.5rem;
            letter-spacing: 0.3px;
        }

        .alert-box {
            background-color: #FDF2F2;
            color: #9B1C1C;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.3rem;
            text-align: center;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 0.45rem;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1.1rem;
            border: 2px solid #EAEAEA;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .password-wrapper input {
            padding-right: 2.8rem;
        }

        .form-group input:focus {
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(234, 168, 0, 0.15);
        }

        .btn-toggle-pass {
            position: absolute;
            right: 0.9rem;
            background: none;
            border: none;
            color: #777;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: color 0.2s;
        }

        .btn-toggle-pass:hover {
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
            margin-top: 0.6rem;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-submit:hover {
            background: var(--brand-yellow-hover);
            transform: translateY(-1px);
        }

        .login-footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #555;
        }

        .login-footer-text a {
            color: var(--brand-yellow);
            font-weight: 700;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .login-footer-text a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        @media (max-width: 950px) {
            .header-site {
                padding: 1.2rem 1.5rem;
            }
            .nav-principal {
                gap: 1.4rem;
            }
        }

        @media (max-width: 600px) {
            .login-box {
                margin: 2.5rem 1.5rem;
                padding: 2rem 1.5rem;
            }
            .nav-principal a:not(.user-pill) {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- ENCABEZADO -->
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
    <div class="login-box">
        <div class="login-icon">
            <i class="bi bi-person-circle"></i>
        </div>
        <h1 class="login-title">Iniciar Sesión</h1>

        <?php if (!empty($error)): ?>
            <div class="alert-box">
                <i class="bi bi-exclamation-triangle-fill" style="margin-right: 4px;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email_cliente">Correo Electrónico</label>
                <input type="email" id="email_cliente" name="email_cliente" required value="<?= htmlspecialchars($_POST['email_cliente'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password_cliente">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="password_cliente" name="password_cliente" required>
                    <button type="button" class="btn-toggle-pass" onclick="togglePassword('password_cliente', 'icon-login')">
                        <i id="icon-login" class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>

        <p class="login-footer-text">
            ¿No tienes cuenta? <a href="usuario.php">Crear cuenta</a>
        </p>
    </div>
</main>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
}
</script>

</body>
</html>