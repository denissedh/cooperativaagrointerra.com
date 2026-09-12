<?php
session_start();
require_once '../app/config.php'; // Conexión PDO ($pdo)

// Verificar que el cliente tenga una sesión activa
if (!isset($_SESSION['cliente_email']) || !isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

$id_cliente     = $_SESSION['cliente_id'];
$nombre_usuario = $_SESSION['cliente_nombre'] ?? 'Usuario';

// 1. MARCAR NOTIFICACIÓN COMO LEÍDA (Petición GET)
if (isset($_GET['marcar_leida'])) {
    $id_notif = filter_var($_GET['marcar_leida'], FILTER_VALIDATE_INT);
    if ($id_notif) {
        $stmt_update = $pdo->prepare("UPDATE tb_notificaciones SET leido = 1 WHERE id_notificacion = :id_notif AND id_cliente = :id_cliente");
        $stmt_update->execute([':id_notif' => $id_notif, ':id_cliente' => $id_cliente]);
    }
    header("Location: notificaciones.php");
    exit();
}

// 2. MARCAR TODAS COMO LEÍDAS
if (isset($_GET['marcar_todas'])) {
    $stmt_all = $pdo->prepare("UPDATE tb_notificaciones SET leido = 1 WHERE id_cliente = :id_cliente");
    $stmt_all->execute([':id_cliente' => $id_cliente]);
    header("Location: notificaciones.php");
    exit();
}

// 3. OBTENER NOTIFICACIONES DEL CLIENTE
$query = $pdo->prepare("SELECT * FROM tb_notificaciones WHERE id_cliente = :id_cliente ORDER BY fyh_creacion DESC");
$query->execute([':id_cliente' => $id_cliente]);
$notificaciones = $query->fetchAll(PDO::FETCH_ASSOC);

// Contar no leídas
$no_leidas = array_reduce($notificaciones, function ($num, $item) {
    return $num + ($item['leido'] == 0 ? 1 : 0);
}, 0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Notificaciones</title>
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
            overflow-x: hidden;
        }

        /* HEADER */
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

        /* CONTENEDOR PRINCIPAL */
        .notif-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .notif-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }

        .notif-title {
            font-family: var(--fuente-titulos);
            color: var(--naranja-oscuro);
            font-size: 1.8rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-count {
            background-color: #e74c3c;
            color: white;
            font-size: 0.8rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-family: var(--fuente-principal);
        }

        .btn-marcar-todas {
            color: var(--naranja-oscuro);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .btn-marcar-todas:hover {
            color: #c88300;
            text-decoration: underline;
        }

        /* LISTA DE NOTIFICACIONES */
        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notif-card {
            background-color: var(--blanco);
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            border-left: 5px solid #d0d0d0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .notif-card.no-leida {
            border-left-color: var(--naranja-oscuro);
            background-color: #fffdf5;
        }

        .notif-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }

        .notif-content {
            flex-grow: 1;
        }

        .notif-card-title {
            font-size: 1.05rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.3rem;
        }

        .notif-card-text {
            font-size: 0.9rem;
            color: var(--gris-texto);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .notif-date {
            font-size: 0.75rem;
            color: #a0a0a0;
        }

        .btn-check {
            color: var(--naranja-oscuro);
            text-decoration: none;
            font-size: 1.2rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .btn-check:hover {
            opacity: 1;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            background-color: var(--blanco);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        .empty-state span {
            font-size: 3rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gris-texto);
            font-weight: 500;
        }

        .btn-volver {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: var(--gris-texto);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-volver:hover {
            color: var(--naranja-oscuro);
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="../cliente/index.php">
            <img src="../cliente/img_clientes/image.png" alt="Cooperativa Agrointerra">
        </a>
    </div>
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="quienes_somos.php">¿Quiénes Somos?</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="usuario.php" class="user-icon-btn">👤</a></li>
        </ul>
    </nav>
</header>

<main class="notif-container">
    <a href="usuario.php" class="btn-volver">← Volver a Mi Perfil</a>

    <div class="notif-header">
        <h1 class="notif-title">
            🔔 Notificaciones 
            <?php if ($no_leidas > 0): ?>
                <span class="badge-count"><?php echo $no_leidas; ?></span>
            <?php endif; ?>
        </h1>
        <?php if ($no_leidas > 0): ?>
            <a href="notificaciones.php?marcar_todas=1" class="btn-marcar-todas">Marcar todas como leídas</a>
        <?php endif; ?>
    </div>

    <div class="notif-list">
        <?php if (count($notificaciones) > 0): ?>
            <?php foreach ($notificaciones as $notif): ?>
                <div class="notif-card <?php echo $notif['leido'] == 0 ? 'no-leida' : ''; ?>">
                    <div class="notif-content">
                        <div class="notif-card-title"><?php echo htmlspecialchars($notif['titulo']); ?></div>
                        <div class="notif-card-text"><?php echo nl2br(htmlspecialchars($notif['mensaje'])); ?></div>
                        <div class="notif-date">
                            📅 <?php echo date('d/m/Y - h:i A', strtotime($notif['fyh_creacion'])); ?>
                        </div>
                    </div>
                    <?php if ($notif['leido'] == 0): ?>
                        <a href="notificaciones.php?marcar_leida=<?php echo $notif['id_notificacion']; ?>" class="btn-check" title="Marcar como leída">✔</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <span>🔕</span>
                <p>No tienes notificaciones en este momento.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>