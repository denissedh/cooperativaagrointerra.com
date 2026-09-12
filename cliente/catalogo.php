<?php 
require 'config.php';

// 1. Iniciamos la sesión si no se ha iniciado aún
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario_logueado = isset($_SESSION['usuario']) || isset($_SESSION['id_cliente']); 
$foto_perfil = $_SESSION['foto_perfil'] ?? null; 

$id_prod = isset($_GET['id']) ? intval($_GET['id']) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Catálogo</title>
    
    <!-- Tipografías sincronizadas con index.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Montserrat:wght@600;700&family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo_cliente.css"> 

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-dorado: #e69f0c;
            --color-dorado-hover: #c98805;
            --color-primario-agro: #DAA520;
            --color-amarillo-pildora: #F5C300;
            --color-texto-oscuro: #333333;
            --color-blanco: #ffffff;
            --fuente-titulos-serif: 'Playfair Display', serif;
            --fuente-cuerpo-sans: 'Montserrat', Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: var(--fuente-cuerpo-sans);
            background-color: #f9f9f9;
        }

        .header-site {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 4rem 1.2rem 3rem;
            border-bottom: 3px solid rgb(245, 242, 242);
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            z-index: 1000;
        }

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

        /* Icono de usuario idéntico */
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

        /* ==========================================================
           ESTILOS DE CONTENIDO CATÁLOGO
           ========================================================== */
        .catalogo-hero {
            background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('../cliente/img_clientes/c.png') center/cover no-repeat; 
            position: relative;
            padding-top: 180px;
            padding-bottom: 150px;
            text-align: center;
        }

        .catalogo-hero h1 {
            font-family: var(--fuente-titulos-serif);
            font-size: 4rem;
            color: var(--color-blanco);
            letter-spacing: 1px;
            font-weight: 400;
            text-shadow: 0 3px 10px rgba(0,0,0,0.5);
            margin: 0;
            text-transform: uppercase;
        }

        .catalogo-selector-wrapper {
            position: relative;
            max-width: 1200px;
            margin: -100px auto 4rem auto;
            padding: 0 3rem;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .catalogo-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); 
            gap: 1.5rem;
            width: 100%;
        }

        .card-producto-grid {
            background-color: var(--color-amarillo-pildora);
            border-radius: 100px;
            padding: 2.7rem 1.5rem;
            text-align: center;
            color: var(--color-blanco);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-producto-grid:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .img-oval-container {
            background-color: var(--color-blanco);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            padding: 15px;
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        }

        .img-oval-container img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .card-producto-grid h3 {
            font-family: var(--fuente-cuerpo-sans);
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 1px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0 1.5rem 0;
            color: var(--color-blanco);
        }

        .btn-card-info {
            background-color: var(--color-blanco);
            color: var(--color-amarillo-pildora);
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-family: var(--fuente-cuerpo-sans);
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s ease, color 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-card-info:hover {
            background-color: var(--color-texto-oscuro);
            color: var(--color-blanco);
        }

        .slider-arrow {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            user-select: none;
            transition: color 0.3s ease, transform 0.2s ease;
            padding: 1rem;
        }

        .slider-arrow:hover {
            color: var(--color-blanco);
            transform: scale(1.1);
        }

        /* Detalle de producto */
        .detalle-container {
            max-width: 1200px;
            margin: 150px auto 5rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr 250px;
            gap: 3rem;
        }

        .etiqueta-pildora { background: #eee; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; }
        .badge-seccion {
            background-color: var(--color-amarillo-pildora);
            color: var(--color-blanco);
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.4rem 1.8rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-family: var(--fuente-cuerpo-sans);
            text-transform: uppercase;
        }

        .lista-detalle {
            list-style: none;
            padding-left: 0;
            margin: 0 0 1.5rem 0;
        }

        .lista-detalle li {
            position: relative;
            padding-left: 1.8rem;
            margin-bottom: 0.75rem;
            color: #666666;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .lista-detalle li::before {
            content: "🍃"; 
            position: absolute;
            left: 0;
            top: 0;
            font-size: 0.85rem;
        }

        .grid-dos-columnas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .btn-pildora { 
            background: var(--color-amarillo-pildora); 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 30px; 
            cursor: pointer; 
            text-transform: uppercase; 
            font-weight: 600; 
            font-size: 0.9rem;
            transition: background 0.3s;
            width: 100%;
        }
        
        .btn-pildora:hover { background-color: #e0b000; }

        .btn-pildora-secundario { 
            background: #eee; 
            color: #333; 
            border: none; 
            padding: 10px; 
            border-radius: 30px; 
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.3s;
            width: 100%;
        }
        .btn-pildora-secundario:hover { background-color: #e0e0e0; }

        .card-relacionado { border: 1px solid #eee; padding: 10px; text-align: center; border-radius: 10px; margin-bottom: 10px; background: white;}
        .card-relacionado img { max-width: 50px; max-height: 50px; object-fit: contain; }

        /* Media query responsive */
        @media (max-width: 768px) {
            .header-site {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
                position: relative;
            }

            .nav-principal ul {
                gap: 1.2rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-principal a {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- HEADER DEL SITIO UNIFICADO -->
<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra" class="logo-top-img">
        </a>
    </div>
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php">INICIO</a></li>
            <li><a href="catalogo.php" class="active">CATÁLOGO</a></li>
            <li><a href="quienes_somos.php">¿QUIÉNES SOMOS?</a></li>
            <li><a href="contacto.php">CONTACTO</a></li>
            <li>
                <a href="usuario.php" class="user-icon-btn">
                    <div class="user-icon-circle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</header>
<body>

<!-- ENCABEZADO SUPERIOR -->
<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra" class="logo-top-img">
        </a>
    </div>
    
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php">INICIO</a></li>
            <li><a href="catalogo.php" class="active">CATÁLOGO</a></li>
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

<?php if (!$id_prod): ?>
    
    <!-- 1. VISTA GENERAL DEL CATÁLOGO -->
    <div class="catalogo-hero">
        <h1>NUESTROS PRODUCTOS</h1>
    </div>

    <div class="catalogo-selector-wrapper">
        <div class="slider-arrow arrow-left">&#10094;</div>

        <div class="catalogo-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM tb_almacen LIMIT 4"); 
            
            while ($p = $stmt->fetch(PDO::FETCH_ASSOC)): 
                $ruta_img = !empty($p['imagen']) ? "../almacen/img_productos/".$p['imagen'] : "../almacen/img_productos/default_producto.jpg";
            ?>
                <div class="card-producto-grid">
                    <div class="img-oval-container">
                        <img src="<?= $ruta_img ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <a href="catalogo.php?id=<?= $p['id_producto'] ?>" class="btn-card-info">Más información</a>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="slider-arrow arrow-right">&#10095;</div>
    </div>

<?php else: ?>
    
    <!-- 2. VISTA DETALLE DE PRODUCTO -->
    <?php
    $stmt = $pdo->prepare("SELECT * FROM tb_almacen WHERE id_producto = ?");
    $stmt->execute([$id_prod]);
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prod) {
        echo "<div style='margin-top:150px; text-align:center;'>Producto no encontrado. <a href='catalogo.php'>Volver al catálogo</a></div>";
    } else {
        $ruta_img_detalle = !empty($prod['imagen']) ? "../almacen/img_productos/".$prod['imagen'] : "../almacen/img_productos/default_producto.jpg";
    ?>
        <div class="detalle-container">
            
            <!-- Columna 1: Nombre, Imagen y Badges básicos -->
            <div class="detalle-img-box">
                <h1 style="font-family: var(--fuente-titulos-serif); color: var(--color-texto-oscuro); font-size: 2.5rem; margin-bottom: 1rem; text-align: left; text-transform: uppercase;">
                    <?= htmlspecialchars($prod['nombre']) ?>
                </h1>
                <img src="<?= $ruta_img_detalle ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" style="max-width: 100%; height: auto; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                
                <div class="badges-info" style="margin-top: 1.5rem; display: flex; gap: 10px; justify-content: flex-start;">
                    <span class="etiqueta-pildora">Formato: <?= htmlspecialchars($prod['cantidad'] ?? 'N/A') ?></span>
                    <span class="etiqueta-pildora">Stock: <?= htmlspecialchars($prod['stock'] ?? '0') ?></span>
                </div>
            </div>

            <!-- Columna 2: Info detallada -->
            <div>
                <div class="badge-seccion">Propiedades</div>
                <ul class="lista-detalle">
                    <?php 
                    $propiedades = explode("\n", $prod['propiedades'] ?? '');
                    foreach ($propiedades as $p): 
                        if (trim($p) !== ''): ?>
                            <li><?= htmlspecialchars(trim($p)) ?></li>
                        <?php endif; 
                    endforeach; 
                    if (empty(array_filter($propiedades, 'trim'))) echo "<li>No hay propiedades registradas.</li>";
                    ?>
                </ul>

                <div class="grid-dos-columnas">
                    <div>
                        <div class="badge-seccion">Ingredientes</div>
                        <ul class="lista-detalle">
                            <?php 
                            $ingredientes = explode("\n", $prod['ingredientes'] ?? '');
                            foreach ($ingredientes as $ing): 
                                if (trim($ing) !== ''): ?>
                                    <li><?= htmlspecialchars(trim($ing)) ?></li>
                                <?php endif; 
                            endforeach; 
                            if (empty(array_filter($ingredientes, 'trim'))) echo "<li>Información no disponible.</li>";
                            ?>
                        </ul>
                    </div>

                    <div>
                        <div class="badge-seccion">Beneficios</div>
                        <ul class="lista-detalle">
                            <?php 
                            $beneficios = explode("\n", $prod['beneficios'] ?? '');
                            foreach ($beneficios as $b): 
                                if (trim($b) !== ''): ?>
                                    <li><?= htmlspecialchars(trim($b)) ?></li>
                                <?php endif; 
                            endforeach; 
                            if (empty(array_filter($beneficios, 'trim'))) echo "<li>No hay beneficios registrados.</li>";
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Columna 3: Precio, Acciones y Relacionados -->
            <div class="acciones-relacionados">
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 2rem; border: 1px solid #eee;">
                    <p style="font-size: 0.9rem; color: #777; margin: 0;">Precio venta:</p>
                    <p style="font-size: 2.2rem; font-weight: 700; color: var(--color-primario-agro); margin: 0 0 1.5rem 0;">
                        $<?= number_format($prod['precio_venta'], 2) ?>
                    </p>

                    <form method="POST" action="agregar_carrito.php" style="display: flex; flex-direction: column; gap: 1rem;">
                        <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                            <label style="font-weight:600; color:var(--color-texto-oscuro); font-size: 0.9rem;">Cantidad:</label>
                            <input type="number" name="cantidad" value="1" min="1" max="<?= htmlspecialchars($prod['stock'] ?? 10) ?>" 
                                   style="padding:0.7rem; border:1px solid #ddd; border-radius:30px; text-align:center; font-size:1rem; width: 60px;">
                        </div>
                        
                        <button type="submit" name="comprar_ahora" class="btn-pildora">Comprar ahora</button>
                        <button type="submit" name="agregar" class="btn-pildora-secundario">🛒 Agregar al carrito</button>
                    </form>
                </div>

                <div class="relacionados-box" style="padding: 0 10px;">
                    <p style="font-size: 0.85rem; color: #777; margin-bottom: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-align: center;">También te puede interesar</p>
                    <?php
                    $rel = $pdo->prepare("SELECT * FROM tb_almacen WHERE id_producto != ? ORDER BY RAND() LIMIT 2");
                    $rel->execute([$id_prod]);
                    while ($r = $rel->fetch(PDO::FETCH_ASSOC)):
                        $ruta_rel = !empty($r['imagen']) ? "../almacen/img_productos/".$r['imagen'] : "../almacen/img_productos/default_producto.jpg";
                    ?>
                        <div class="card-relacionado" style="border: 1px solid #eee; transition: box-shadow 0.3s;">
                            <img src="<?= $ruta_rel ?>" alt="<?= htmlspecialchars($r['nombre']) ?>">
                            <h4 style="font-size: 0.8rem; margin: 0.6rem 0 0.3rem 0; text-transform: uppercase; color: var(--color-texto-oscuro); font-family: var(--fuente-cuerpo-sans);"><?= htmlspecialchars($r['nombre']) ?></h4>
                            <p style="font-weight: bold; font-size: 0.95rem; color: var(--color-amarillo-pildora); margin: 0 0 0.6rem 0;">$<?= number_format($r['precio_venta'], 2) ?></p>
                            <a href="catalogo.php?id=<?= $r['id_producto'] ?>" style="font-size: 0.75rem; color: var(--color-primario-agro); text-decoration: none; font-weight: 600;">Ver detalle →</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php endif; ?>

</body>
</html>