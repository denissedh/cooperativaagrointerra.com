<?php
session_start();
require_once '../app/config.php';

// 1. Obtenemos el ID del cliente (usando cliente_id o sesion_id según corresponda)
$id_cliente = $_SESSION['cliente_id'] ?? $_SESSION['sesion_id'] ?? 0;

// 2. Si no ha iniciado sesión, redirigir a login_cliente.php o usuario.php (NUNCA a carrito.php)
if ($id_cliente === 0) {
    header("Location: login_cliente.php");
    exit();
}

$productos_carrito = [];
$subtotal_general = 0;

try {
    // Consulta: productos de venta ABIERTA del cliente
    $sql_carrito = "SELECT 
                        c.id_carrito, 
                        c.cantidad, 
                        c.id_producto,
                        a.precio_venta,
                        a.nombre, 
                        a.imagen, 
                        a.stock 
                    FROM tb_carrito AS c 
                    INNER JOIN tb_almacen AS a 
                        ON c.id_producto = a.id_producto
                    INNER JOIN tb_ventas AS v 
                        ON c.id_venta = v.id_venta
                    WHERE 
                        v.id_cliente = :id_cliente 
                        AND v.estado = 'ABIERTA'
                    ORDER BY c.id_carrito DESC";

    $query_carrito = $pdo->prepare($sql_carrito);
    $query_carrito->execute([':id_cliente' => $id_cliente]);
    $productos_carrito = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — Mi Carrito de Compras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --naranja-claro: #F9CA10;  
            --naranja-oscuro: #F1A306; 
            --gris-texto: #6D6E70;    
            --blanco: #FFFFFF;
            --rojo-eliminar: #e74c3c;
            --rojo-hover: #c0392b;
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
            padding: 1.2rem 8%;
            border-bottom: 1.5px solid #E5A811;
        }

        .logo-container img {
            height: 50px;
            width: auto;
            display: block;
        }

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
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
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
            font-size: 0.9rem;
            transition: opacity 0.2s ease;
        }

        .user-icon:hover {
            opacity: 0.85;
        }

        .carrito-container {
            max-width: 1100px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: var(--blanco);
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-top: 5px solid var(--naranja-oscuro);
        }

        .header-seccion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease;
        }

        .btn-regresar:hover {
            color: var(--naranja-oscuro);
        }

        .tabla-carrito {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .tabla-carrito th {
            background-color: #f4f4f4;
            color: var(--gris-texto);
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 14px 12px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        .tabla-carrito td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .detalles-prod {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .img-producto {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .precio-tag {
            color: #555;
            font-weight: 600;
        }

        .subtotal-tag {
            color: #2e7d32;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .btn-eliminar {
            color: var(--rojo-eliminar);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #fde8e8;
            transition: all 0.2s ease;
        }

        .btn-eliminar:hover {
            background-color: var(--rojo-eliminar);
            color: var(--blanco);
        }

        .resumen-compra {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .card-resumen {
            background-color: #fafafa;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #eee;
            width: 320px;
        }

        .card-resumen h3 {
            font-size: 1.2rem;
            color: var(--gris-texto);
            margin-bottom: 1rem;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.5rem;
        }

        .fila-resumen {
            display: flex;
            justify-content: space-between;
            font-size: 1.15rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .btn-checkout {
            display: block;
            width: 100%;
            text-align: center;
            background: linear-gradient(180deg, var(--naranja-claro) 0%, var(--naranja-oscuro) 100%);
            color: var(--blanco);
            padding: 0.8rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
        }

        .carrito-vacio {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gris-texto);
        }

        .carrito-vacio a {
            color: var(--naranja-oscuro);
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="img_clientes/image.png" alt="Cooperativa Agrointerra" onerror="this.src='../cliente/img_clientes/image.png';">
        </a>
    </div>
    <nav class="nav-principal">
        <ul>
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="quienes_somos.php">¿Quiénes Somos?</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li>
                <a href="usuario.php" class="user-icon" title="Mi Perfil">
                    <i class="fa-solid fa-user"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<main class="carrito-container">
    <div class="header-seccion">
        <h1 class="titulo-seccion">🛒 Mi Carrito</h1>
        <a href="catalogo.php" class="btn-regresar">
            <i class="fa-solid fa-arrow-left"></i> Volver al catálogo
        </a>
    </div>
    
    <?php if (!empty($productos_carrito)): ?>
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th style="text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_carrito as $item): 
                    $subtotal_producto = (float)$item['precio_venta'] * (int)$item['cantidad'];
                    $subtotal_general += $subtotal_producto;
                    
                    $nombre_imagen = $item['imagen'] ?? '';
                    $dir_imagenes = '../almacen/img_productos/';
                    $ruta_imagen = (!empty($nombre_imagen) && file_exists($dir_imagenes . $nombre_imagen)) 
                        ? $dir_imagenes . htmlspecialchars($nombre_imagen) 
                        : '../almacen/img_productos/no-image.png';
                ?>
                    <tr>
                        <td>
                            <div class="detalles-prod">
                                <img src="<?php echo $ruta_imagen; ?>" class="img-producto" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                                <span><strong><?php echo htmlspecialchars($item['nombre']); ?></strong></span>
                            </div>
                        </td>
                        <!-- Ahora muestra el total calculado en la columna 'Precio Unitario' -->
                        <td class="precio-tag">$<?php echo number_format($subtotal_producto, 2); ?></td>
                        <td><?php echo (int)$item['cantidad']; ?></td>
                        <!-- Ahora muestra el precio base del producto en la columna 'Subtotal' -->
                        <td class="subtotal-tag">$<?php echo number_format($item['precio_venta'], 2); ?></td>
                        <td style="text-align: center;">
                            <a href="eliminar_carrito.php?id=<?php echo (int)$item['id_carrito']; ?>" 
                               class="btn-eliminar" 
                               onclick="return confirm('¿Seguro que deseas eliminar este producto del carrito?');">
                                <i class="fa-solid fa-trash-can"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumen-compra">
            <div class="card-resumen">
                <h3>Resumen del Pedido</h3>
                <div class="fila-resumen">
                    <span>Total:</span>
                    <span>$<?php echo number_format($subtotal_general, 2); ?></span>
                </div>
                <a href="procesar_pago.php" class="btn-checkout">Proceder al Pago</a>
            </div>
        </div>

    <?php else: ?>
        <div class="carrito-vacio">
            <p>Tu carrito está actualmente vacío.</p>
            <br>
            <a href="catalogo.php">← Explorar el catálogo de productos</a>
        </div>
    <?php endif; ?>
</main>

</body>
</html>