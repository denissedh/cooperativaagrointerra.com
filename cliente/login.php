<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Iniciar Sesión</title>
    <style>
        :root {
            --naranja-claro: #F9CA10;  
            --naranja-oscuro: #F1A306; 
            --gris-texto: #6D6E70;    
            --blanco: #FFFFFF;
            
            --fuente-principal: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --fuente-titulos: 'Arial Black', Gadget, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--fuente-principal);
            background-color: #fcfcfc;
            color: #333;
        }

        .header-site {
            background-color: var(--blanco);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 10%;
            border-bottom: 2px solid var(--naranja-claro);
        }

        .logo-container img {
            height: 45px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .nav-principal ul {
            list-style: none;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-principal a {
            text-decoration: none;
            color: var(--naranja-oscuro);
            font-weight: bold;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .nav-principal .user-icon-btn {
            background-color: var(--naranja-oscuro);
            color: var(--blanco);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .login-container {
            max-width: 450px;
            margin: 4rem auto;
            padding: 2.5rem;
            background-color: var(--blanco);
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-top: 5px solid var(--naranja-oscuro);
        }

        .titulo-login {
            font-family: var(--fuente-titulos);
            color: var(--naranja-oscuro);
            font-size: 1.8rem;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .subtitulo-login {
            text-align: center;
            color: var(--gris-texto);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-login {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-group label {
            font-weight: bold;
            font-size: 0.85rem;
            color: var(--gris-texto);
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: var(--fuente-principal);
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .form-group input:focus {
            border-color: var(--naranja-oscuro);
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 2.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.8rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: var(--gris-texto);
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        .toggle-password:hover {
            color: var(--naranja-oscuro);
        }

        .btn-submit {
            background: linear-gradient(180deg, var(--naranja-claro) 0%, var(--naranja-oscuro) 100%);
            color: var(--blanco);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .registro-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gris-texto);
        }

        .registro-link a {
            color: var(--naranja-oscuro);
            font-weight: bold;
            text-decoration: none;
        }

        .registro-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra">
        </a>
    </div>
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php">INICIO</a></li>
            <li><a href="quienes_somos.php">¿QUIÉNES SOMOS?</a></li>
            <li><a href="catalogo.php">CATÁLOGO</a></li>
            <li><a href="contacto.php">CONTACTO</a></li>
            <li><a href="login.php" class="user-icon-btn">👤</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="login-container">
        <h1 class="titulo-login">Iniciar Sesión</h1>
        <p class="subtitulo-login">Ingresa tus credenciales para acceder</p>

        <form action="procesar_login.php" method="POST" class="form-login">
            <div class="form-group">
                <label for="login_user">Correo Electrónico o Usuario</label>
                <input type="text" id="login_user" name="login_user" placeholder="ejemplo@correo.com o usuario" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)" aria-label="Mostrar u ocultar contraseña">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>

        <div class="registro-link">
            ¿Aún no tienes cuenta? <a href="usuario.php">Regístrate aquí</a>
        </div>
    </div>
</main>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    }
</script>

</body>
</html>