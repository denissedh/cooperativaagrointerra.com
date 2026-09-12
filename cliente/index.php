<?php 
require 'config.php'; 

// 1. Iniciamos la sesión si no se ha iniciado aún
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario_logueado = isset($_SESSION['usuario']) || isset($_SESSION['id_cliente']); 
$foto_perfil = $_SESSION['foto_perfil'] ?? null; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra | Inicio</title>
<meta name="description" content="Sitio oficial de Cooperativa Agrointerra. Consulta nuestro catálogo de productos y servicios.">
<meta name="keywords" content="Cooperativa Agrointerra, catálogo, ventas">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-dorado: #e69f0c;
            --color-dorado-hover: #c98805;
        }

        body, html {
            height: 100%;
            width: 100%;
            font-family: 'Montserrat', Arial, sans-serif;
            overflow-x: hidden;
        }

        /* --- CONTENEDOR HERO CON FONDO --- */
        .hero-banner {
            position: relative;
            min-height: 100vh;
            width: 100%;
            /* Imagen de fondo del campo de viñedos */
            background: url('../cliente/img_clientes/a.png') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        /* Degradado suave para asegurar legibilidad del texto en el cielo */
        .hero-banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 60%);
            pointer-events: none;
        }

        /* --- CABECERA --- */
        header.header-site {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 4rem 1.2rem 3rem;
    background: transparent;
    border-bottom: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 100;
}

        /* Logotipo superior */
        .logo-container a {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .logo-top-img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        /* Menú de Navegación */
        .nav-principal ul {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2.2rem;
        }

        .nav-principal a {
            text-decoration: none;
            color: var(--color-dorado);
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .nav-principal a:hover,
        .nav-principal a.active {
            opacity: 0.85;
        }

        /* Ícono o Foto de Perfil de Usuario */
        .user-avatar-link,
        .user-icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .user-icon-circle {
            width: 38px;
            height: 38px;
            background-color: var(--color-dorado);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 18px;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .user-icon-circle:hover {
            transform: scale(1.08);
            background-color: var(--color-dorado-hover);
        }

        .user-avatar-nav {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            transition: transform 0.2s ease;
        }

        .user-avatar-nav:hover {
            transform: scale(1.08);
        }

        /* --- SECCIÓN PRINCIPAL / CENTRO --- */
        .hero-center-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 5;
            padding-bottom: 9vh; /* Eleva ligeramente el logo hacia el tercio superior/cielo */
            text-align: center;
        }

        .hero-logo-container {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Imagen del logotipo principal central */
        .hero-logo-container img {
            max-width: 620px;
            width: 85vw;
            height: auto;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
        }

        /* Botón estilo píldora 'CATÁLOGO' */
        .btn-pildora {
            display: inline-block;
            background-color: var(--color-dorado);
            color: #ffffff;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.7rem 4rem;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .btn-pildora:hover {
            background-color: var(--color-dorado-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-site {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-principal ul {
                gap: 1.2rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-principal a {
                font-size: 0.85rem;
            }

            .btn-pildora {
                padding: 0.6rem 2.8rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="hero-banner">
    <header class="header-site">
        <div class="logo-container">
            <a href="index.php">
                <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra" class="logo-top-img">
            </a>
        </div>
        
        <nav class="nav-principal">
            <ul>
                <li><a href="index.php" class="active">INICIO</a></li>
                <li><a href="catalogo.php">CATÁLOGO</a></li>
                <li><a href="quienes_somos.php">¿QUIÉNES SOMOS?</a></li>
                <li><a href="contacto.php">CONTACTO</a></li>
                
                <!-- Botón / Foto del perfil -->
                <li>
                    <a href="usuario.php" class="<?php echo ($usuario_logueado && !empty($foto_perfil)) ? 'user-avatar-link' : 'user-icon-btn'; ?>">
                        <?php if ($usuario_logueado && !empty($foto_perfil)): ?>
                            <img src="../cliente/img_clientes/<?php echo htmlspecialchars($foto_perfil); ?>" alt="Perfil" class="user-avatar-nav">
                        <?php else: ?>
                            <div class="user-icon-circle">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- CONTENIDO CENTRAL HERO -->
    <main class="hero-center-content">
        <div class="hero-logo-container">
            <!-- Logotipo grande en el centro -->
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra">
        </div>
        
        <!-- Botón Píldora Catálogo -->
        <a href="catalogo.php" class="btn-pildora">CATÁLOGO</a>
    </main>
</div>

</body>
</html>