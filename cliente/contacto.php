<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Contáctanos</title>
    <!-- FontAwesome para los iconos exactos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Fuente Montserrat en pesos bold/extrabold -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-naranja: #f39200;
            --color-banner: #f58220;
            --fuente: 'Montserrat', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--fuente);
            background-color: #ffffff;
            color: var(--color-naranja);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* --- Header / Barra de Navegación --- */
        .header-site {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 4rem;
            position: relative;
        }

        /* Línea divisoria dorada */
        .header-site::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 2rem;
            right: 2rem;
            height: 1.5px;
            background-color: var(--color-naranja);
        }

        .logo-container img {
            height: 48px;
            object-fit: contain;
        }

        .nav-principal ul {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2.2rem;
        }

        .nav-principal a {
            text-decoration: none;
            color: var(--color-naranja);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }

        .nav-principal a:hover {
            opacity: 0.8;
        }

        /* Icono de usuario circular relleno */
        .user-icon-btn {
            background-color: var(--color-naranja);
            color: #ffffff !important;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        /* --- Contenedor Principal --- */
        .contacto-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem 1rem;
            text-align: center;
        }

        /* Botón/Caja naranja "CONTÁCTANOS" */
        .banner-contactanos {
            background-color: var(--color-banner);
            color: #ffffff;
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            padding: 0.9rem 4.5rem;
            border-radius: 6px;
            margin-bottom: 3.5rem;
            display: inline-block;
        }

        /* Bloque de 3 columnas de datos */
        .contacto-grid {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 4.5rem;
            max-width: 1100px;
            width: 100%;
            margin-bottom: 3rem;
        }

        .contacto-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Iconos redondos de Teléfono, Ubicación y Correo */
        .icon-circle {
            background-color: var(--color-banner);
            color: #ffffff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        /* Estilo tipográfico de los textos informativos */
        .contacto-texto {
            color: var(--color-naranja);
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.45;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Logo inferior centrado */
        .logo-bottom-container {
            margin: 1rem 0 0.5rem;
        }

        .logo-bottom-container img {
            width: 260px;
            max-width: 90%;
            height: auto;
        }

        /* --- Footer --- */
        .footer-site {
            text-align: center;
            padding-bottom: 1.2rem;
        }

        .footer-site p {
            color: #a0a0a0;
            font-size: 0.82rem;
            font-weight: 500;
        }

        /* Adaptabilidad en pantallas pequeñas */
        @media (max-width: 768px) {
            .header-site {
                flex-direction: column;
                gap: 1rem;
                padding: 1.2rem;
            }

            .nav-principal ul {
                gap: 1.2rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .contacto-grid {
                flex-direction: column;
                gap: 2.2rem;
            }

            .banner-contactanos {
                font-size: 1.4rem;
                padding: 0.8rem 2.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Cabecera -->
    <header class="header-site">
        <div class="logo-container">
            <a href="index.php">
                <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra">
            </a>
        </div>
        <nav class="nav-principal">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="catalogo.php">Catálogo</a></li>
                <li><a href="quienes_somos.php">¿Quiénes Somos?</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li>
                    <a href="usuario.php" class="user-icon-btn" aria-label="Perfil">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Contenido Central -->
    <main class="contacto-wrapper">
        <div class="banner-contactanos">
            CONTÁCTANOS
        </div>

        <section class="contacto-grid">
            <!-- Teléfono -->
            <div class="contacto-col">
                <div class="icon-circle">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <p class="contacto-texto">636 101 7629</p>
            </div>

            <!-- Dirección -->
            <div class="contacto-col">
                <div class="icon-circle">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <p class="contacto-texto">
                    CALLE HORTALIZAS #3935 COLONIA SAN JUAN<br>
                    ASCENSION, CHIHUAHUA
                </p>
            </div>

            <!-- Correo -->
            <div class="contacto-col">
                <div class="icon-circle">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <p class="contacto-texto">COOP.AGROINTERRA GMAIL.COM</p>
            </div>
        </section>

        <div class="logo-bottom-container">
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra">
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="footer-site">
        <p>Copyright © 2026</p>
    </footer>

</body>
</html>