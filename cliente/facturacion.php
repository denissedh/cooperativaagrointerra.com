<?php
session_start();
require_once '../app/config.php'; // Conexión a la base de datos PDO ($pdo)

// 1. VERIFICACIÓN DE SESIÓN ACTIVA (Usa 'cliente_email' o 'sesion_email' como respaldo)
if (!isset($_SESSION['cliente_email']) && !isset($_SESSION['sesion_email'])) {
    header("Location: login_cliente.php");
    exit();
}

// 2. OBTENCIÓN DE VARIABLES DE SESIÓN CORREGIDAS
$id_cliente     = $_SESSION['cliente_id'] ?? $_SESSION['sesion_id'] ?? 0;
$nombre_usuario = $_SESSION['cliente_nombre'] ?? $_SESSION['sesion_nombre'] ?? 'Usuario';
$email_usuario  = $_SESSION['cliente_email'] ?? $_SESSION['sesion_email'] ?? '';

// Variables de estado/mensaje
$mensaje_exito = '';
$error_mensaje = '';

// Obtener datos completos del cliente
$query_cliente = $pdo->prepare("SELECT * FROM tb_clientes WHERE id_cliente = :id LIMIT 1");
$query_cliente->execute([':id' => $id_cliente]);
$cliente_info = $query_cliente->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'solicitar_factura') {
        $id_venta = filter_input(INPUT_POST, 'id_venta', FILTER_VALIDATE_INT);
        $rfc      = trim($_POST['rfc_receptor'] ?? '');
        $razon    = trim($_POST['razon_social'] ?? '');
        $uso_cfdi = trim($_POST['uso_cfdi'] ?? 'G03');

        if ($id_venta && !empty($rfc) && !empty($razon)) {
            // Verificar que la venta le pertenezca al cliente
            $q_v = $pdo->prepare("SELECT * FROM tb_ventas WHERE id_venta = :id_venta AND id_cliente = :id_cliente LIMIT 1");
            $q_v->execute([':id_venta' => $id_venta, ':id_cliente' => $id_cliente]);
            $venta_data = $q_v->fetch(PDO::FETCH_ASSOC);

            if ($venta_data) {
                // Generar Folio Fiscal (UUID) y número de factura
                $folio_fiscal = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
                $nro_factura = 'FAC-' . str_pad($id_venta, 6, '0', STR_PAD_LEFT);

                // Insertar o actualizar registro en tb_facturas
                $q_ins = $pdo->prepare("INSERT INTO tb_facturas 
                    (id_venta, id_cliente, nro_factura, folio_fiscal, rfc_receptor, razon_social, uso_cfdi, monto_total, fecha_emision, estado_factura) 
                    VALUES (:id_venta, :id_cliente, :nro_factura, :folio_fiscal, :rfc, :razon, :uso_cfdi, :monto, :fecha, 'EMITIDA')
                    ON DUPLICATE KEY UPDATE 
                        rfc_receptor = VALUES(rfc_receptor),
                        razon_social = VALUES(razon_social),
                        uso_cfdi = VALUES(uso_cfdi),
                        estado_factura = 'EMITIDA'");
                
                $exito = $q_ins->execute([
                    ':id_venta'     => $id_venta,
                    ':id_cliente'   => $id_cliente,
                    ':nro_factura'  => $nro_factura,
                    ':folio_fiscal' => strtoupper($folio_fiscal),
                    ':rfc'          => strtoupper($rfc),
                    ':razon'        => strtoupper($razon),
                    ':uso_cfdi'     => $uso_cfdi,
                    ':monto'        => $venta_data['total_pagado'] ?? 0,
                    ':fecha'        => date('Y-m-d H:i:s')
                ]);

                if ($exito) {
                    $mensaje_exito = "¡Factura $nro_factura emitida con éxito!";
                } else {
                    $error_mensaje = "No se pudo generar la factura. Inténtalo nuevamente.";
                }
            } else {
                $error_mensaje = "La orden especificada no es válida o no te pertenece.";
            }
        } else {
            $error_mensaje = "Por favor, completa todos los datos fiscales obligatorios.";
        }
    }
}

// 3. OBTENER ÚNICAMENTE LAS VENTAS COMPLETADAS DEL CLIENTE
$sql_facturas = "SELECT 
                    v.id_venta, 
                    v.nro_venta, 
                    v.total_pagado, 
                    v.fyh_creacion AS fecha_venta,
                    f.id_factura,
                    f.nro_factura,
                    f.folio_fiscal,
                    f.rfc_receptor,
                    f.razon_social,
                    f.uso_cfdi,
                    f.fecha_emision,
                    COALESCE(f.estado_factura, 'PENDIENTE') AS estado_factura
                 FROM tb_ventas AS v
                 LEFT JOIN tb_facturas AS f ON v.id_venta = f.id_venta
                 WHERE v.id_cliente = :id_cliente 
                   AND v.estado = 'COMPLETADA'
                 ORDER BY v.id_venta DESC";

$query_facturas = $pdo->prepare($sql_facturas);
$query_facturas->execute([':id_cliente' => $id_cliente]);
$facturas_lista = $query_facturas->fetchAll(PDO::FETCH_ASSOC);

// CÁLCULO DE MÉTRICAS
$facturas_emitidas_cnt = 0;
$total_facturado = 0;
foreach ($facturas_lista as $item) {
    if ($item['estado_factura'] === 'EMITIDA') {
        $facturas_emitidas_cnt++;
        $total_facturado += $item['total_pagado'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Mi Facturación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --naranja-claro: #F9CA10;  
            --naranja-oscuro: #F1A306; 
            --gris-texto: #6D6E70;    
            --blanco: #FFFFFF;
            --verde-exito: #27ae60;
            --fuente-principal: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --fuente-titulos: 'Arial Black', Gadget, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--fuente-principal);
            background-color: #fcfcfc;
            color: #333;
        }

        /* HEADER */
        .header-site {
            background-color: var(--blanco);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 8%;
            border-bottom: 1.5px solid #E5A811;
        }

        .logo-container img { height: 50px; width: auto; display: block; }

        .nav-principal ul {
            list-style: none;
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-principal a {
            text-decoration: none;
            color: #E5A811;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
        }

        .user-icon {
            background-color: #E5A811;
            color: var(--blanco) !important;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        /* CONTENEDOR FACTURACIÓN */
        .factura-container {
            max-width: 1100px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: var(--blanco);
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-top: 5px solid var(--naranja-oscuro);
        }

        .header-seccion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }

        .titulo-seccion {
            font-family: var(--fuente-titulos);
            color: var(--naranja-oscuro);
            font-size: 1.8rem;
            text-transform: uppercase;
        }

        .btn-regresar {
            text-decoration: none;
            color: var(--gris-texto);
            font-weight: bold;
            font-size: 0.9rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* METRICAS */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .metric-card {
            background: #fafafa;
            border: 1px solid #eaeaea;
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: var(--naranja-claro);
            color: var(--blanco);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .metric-info h4 { font-size: 0.85rem; color: var(--gris-texto); text-transform: uppercase; }
        .metric-info span { font-size: 1.4rem; font-weight: bold; color: #333; }

        /* TABLA FACTURAS */
        .tabla-facturas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .tabla-facturas th {
            background-color: #f4f4f4;
            color: var(--gris-texto);
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 14px 12px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        .tabla-facturas td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-emitida { background-color: #e8f5e9; color: var(--verde-exito); border: 1px solid #a5d6a7; }
        .badge-pendiente { background-color: #fff8e1; color: var(--naranja-oscuro); border: 1px solid #ffe082; }

        .btn-factura {
            background: linear-gradient(180deg, var(--naranja-claro) 0%, var(--naranja-oscuro) 100%);
            color: var(--blanco);
            border: none;
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-descargar {
            background-color: #34495e;
            color: #fff;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-size: 0.85rem;
            text-decoration: none;
            font-weight: bold;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: var(--blanco);
            width: 100%; max-width: 550px;
            padding: 2rem;
            border-radius: 16px;
            position: relative;
            border-top: 5px solid var(--naranja-oscuro);
        }

        .close-modal { position: absolute; top: 1.2rem; right: 1.5rem; font-size: 1.5rem; cursor: pointer; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.2rem; }
        .form-group label { font-weight: bold; font-size: 0.85rem; color: var(--gris-texto); }
        .form-group input, .form-group select { padding: 0.8rem 1rem; border: 2px solid #e0e0e0; border-radius: 10px; }
        
        .btn-submit-modal {
            width: 100%;
            background: linear-gradient(180deg, var(--naranja-claro) 0%, var(--naranja-oscuro) 100%);
            color: var(--blanco);
            border: none; padding: 0.9rem;
            border-radius: 10px; font-weight: bold; text-transform: uppercase;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="index.php"><img src="img_clientes/image.png" alt="Cooperativa Agrointerra"></a>
    </div>
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="quienes_somos.php">¿Quiénes Somos?</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="usuario.php" class="user-icon" title="Mi Perfil"><i class="fa-solid fa-user"></i></a></li>
        </ul>
    </nav>
</header>

<main class="factura-container">
    <div class="header-seccion">
        <h1 class="titulo-seccion"><i class="fa-solid fa-file-invoice-dollar"></i> Mis Facturas</h1>
        <a href="usuario.php" class="btn-regresar"><i class="fa-solid fa-arrow-left"></i> Volver al Perfil</a>
    </div>

    <?php if (!empty($mensaje_exito)): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje_exito); ?></div>
    <?php endif; ?>

    <?php if (!empty($error_mensaje)): ?>
        <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_mensaje); ?></div>
    <?php endif; ?>

    <!-- TARJETAS METRICAS -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="metric-info">
                <h4>Total Compras</h4>
                <span><?php echo count($facturas_lista); ?></span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon" style="background-color: var(--verde-exito);"><i class="fa-solid fa-file-circle-check"></i></div>
            <div class="metric-info">
                <h4>Facturas Emitidas</h4>
                <span><?php echo $facturas_emitidas_cnt; ?></span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon" style="background-color: var(--naranja-oscuro);"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="metric-info">
                <h4>Monto Facturado</h4>
                <span>$<?php echo number_format($total_facturado, 2); ?></span>
            </div>
        </div>
    </div>

    <!-- LISTA DE COMPRAS Y FACTURAS -->
    <?php if (count($facturas_lista) > 0): ?>
        <table class="tabla-facturas">
            <thead>
                <tr>
                    <th>N° Orden</th>
                    <th>Fecha Venta</th>
                    <th>Monto Total</th>
                    <th>Estado</th>
                    <th>N° Factura</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas_lista as $item): ?>
                    <?php 
                        // Número de factura que se mostrará
                        $num_factura_mostrar = !empty($item['nro_factura']) 
                            ? $item['nro_factura'] 
                            : 'FAC-' . str_pad($item['id_venta'], 6, '0', STR_PAD_LEFT);
                    ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($item['nro_venta']); ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($item['fecha_venta'])); ?></td>
                        <td><strong>$<?php echo number_format($item['total_pagado'], 2); ?></strong></td>
                        <td>
                            <?php if ($item['estado_factura'] === 'EMITIDA'): ?>
                                <span class="badge badge-emitida">Emitida</span>
                            <?php else: ?>
                                <span class="badge badge-pendiente">Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong style="font-family: monospace; font-size: 0.95rem; color: #333; display: block;">
                                <?php echo htmlspecialchars($num_factura_mostrar); ?>
                            </strong>
                            <?php if ($item['estado_factura'] === 'EMITIDA' && !empty($item['folio_fiscal'])): ?>
                                <span style="font-size: 0.75rem; color: #888;">
                                    <?php echo substr($item['folio_fiscal'], 0, 13) . '...'; ?>
                                </span>
                            <?php else: ?>
                                <small style="font-size: 0.75rem; color: #f39c12; font-weight: 600;">(Por emitir)</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['estado_factura'] === 'EMITIDA'): ?>
                                <a href="factura.php?id_venta=<?php echo $item['id_venta']; ?>" target="_blank" class="btn-descargar">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            <?php else: ?>
                                <button class="btn-factura" onclick="abrirModalFactura(<?php echo $item['id_venta']; ?>)">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Facturar
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; padding: 2rem; color:#666;">No hay ventas registradas listas para facturar.</p>
    <?php endif; ?>
</main>

<!-- MODAL DATOS FISCALES -->
<div id="modalFactura" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="cerrarModalFactura()">&times;</span>
        <h2 style="color:var(--naranja-oscuro); margin-bottom:1rem;">DATOS FISCALES</h2>
        <form action="" method="POST">
            <input type="hidden" name="action" value="solicitar_factura">
            <input type="hidden" name="id_venta" id="modal_id_venta">

            <div class="form-group">
                <label for="rfc_receptor">RFC RECEPTOR</label>
                <input type="text" id="rfc_receptor" name="rfc_receptor" placeholder="EJ: XAXX010101000" maxlength="13" required style="text-transform:uppercase;">
            </div>

            <div class="form-group">
                <label for="razon_social">NOMBRE / RAZÓN SOCIAL</label>
                <input type="text" id="razon_social" name="razon_social" value="<?php echo htmlspecialchars($cliente_info['nombre_cliente'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="uso_cfdi">USO DEL CFDI</label>
                <select id="uso_cfdi" name="uso_cfdi" required>
                    <option value="G01">G01 — Adquisición de mercancías</option>
                    <option value="G03" selected>G03 — Gastos en general</option>
                    <option value="P01">P01 — Por definir</option>
                    <option value="S01">S01 — Sin efectos fiscales</option>
                </select>
            </div>

            <button type="submit" class="btn-submit-modal">Solicitar Factura</button>
        </form>
    </div>
</div>

<script>
    function abrirModalFactura(idVenta) {
        document.getElementById('modal_id_venta').value = idVenta;
        document.getElementById('modalFactura').style.display = 'flex';
    }

    function cerrarModalFactura() {
        document.getElementById('modalFactura').style.display = 'none';
    }
</script>

</body>
</html>