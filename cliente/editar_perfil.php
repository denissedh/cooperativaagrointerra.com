<?php
session_start();
require_once '../app/config.php'; // Conexión PDO: $pdo

// 1. Proteger acceso: solo clientes autenticados
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

$id_cliente = $_SESSION['cliente_id'];
$error = '';
$mensaje = '';

// 2. Procesar formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_cliente    = trim($_POST['nombre_cliente'] ?? '');
    $email_cliente     = trim($_POST['email_cliente'] ?? '');
    $celular_cliente   = trim($_POST['celular_cliente'] ?? '');
    $direccion_cliente = trim($_POST['direccion_cliente'] ?? '');
    $password_nueva    = $_POST['password_nueva'] ?? '';

    if (empty($nombre_cliente) || empty($email_cliente) || empty($celular_cliente)) {
        $error = 'Por favor, completa los campos requeridos.';
    } else {
        // Verificar que el correo no esté ocupado por otro usuario
        $query_check = $pdo->prepare("SELECT id_cliente FROM tb_clientes WHERE email_cliente = :email AND id_cliente != :id LIMIT 1");
        $query_check->execute([':email' => $email_cliente, ':id' => $id_cliente]);

        if ($query_check->fetch()) {
            $error = 'El correo electrónico ya está registrado por otra cuenta.';
        } else {
            // Manejo de carga de imagen de perfil
            $foto_nombre = null;
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $permitidas)) {
                    $directorio_destino = '../uploads/perfil/';
                    
                    if (!is_dir($directorio_destino)) {
                        mkdir($directorio_destino, 0755, true);
                    }

                    $foto_nombre = 'perfil_' . $id_cliente . '_' . time() . '.' . $ext;
                    $ruta_final  = $directorio_destino . $foto_nombre;

                    if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_final)) {
                        $error = 'No se pudo guardar la imagen de perfil.';
                    }
                } else {
                    $error = 'Formato de imagen inválido. Usa JPG, PNG o WEBP.';
                }
            }

            if (empty($error)) {
                // Construir consulta dinámica (foto / contraseña)
                $sql = "UPDATE tb_clientes SET 
                            nombre_cliente = :nombre,
                            email_cliente = :email,
                            celular_cliente = :celular,
                            direccion_cliente = :direccion";
                
                $params = [
                    ':nombre'    => $nombre_cliente,
                    ':email'     => $email_cliente,
                    ':celular'   => $celular_cliente,
                    ':direccion' => $direccion_cliente,
                    ':id'        => $id_cliente
                ];

                if ($foto_nombre) {
                    $sql .= ", foto_perfil = :foto";
                    $params[':foto'] = $foto_nombre;
                }

                if (!empty($password_nueva)) {
                    if (!preg_match('/[\W_]/', $password_nueva)) {
                        $error = 'La nueva contraseña debe incluir al menos un carácter especial.';
                    } else {
                        $sql .= ", password_cliente = :password";
                        $params[':password'] = password_hash($password_nueva, PASSWORD_BCRYPT);
                    }
                }

                if (empty($error)) {
                    $sql .= " WHERE id_cliente = :id";
                    $stmt = $pdo->prepare($sql);

                    if ($stmt->execute($params)) {
                        // Actualizar variables de sesión activas
                        $_SESSION['cliente_nombre'] = $nombre_cliente;
                        $_SESSION['cliente_email']  = $email_cliente;

                        // Redirección directa al guardar correctamente
                        header("Location: usuario.php");
                        exit();
                    } else {
                        $error = 'Ocurrió un error al actualizar los datos.';
                    }
                }
            }
        }
    }
}

// 3. Obtener datos actuales del usuario
$query = $pdo->prepare("SELECT * FROM tb_clientes WHERE id_cliente = :id LIMIT 1");
$query->execute([':id' => $id_cliente]);
$cliente = $query->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header("Location: cerrar_sesion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil — Cooperativa Agrointerra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --brand-yellow: #EAA800;
            --brand-yellow-hover: #d69900;
            --text-dark: #222222;
            --bg-body: #FFFFFF;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .header-site {
            background: #FFFFFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 4rem;
            border-bottom: 2px solid var(--brand-yellow);
        }

        .logo-container a { display: inline-flex; align-items: center; text-decoration: none; }
        .logo-container img { height: 52px; object-fit: contain; }

        .nav-principal { display: flex; align-items: center; gap: 2.8rem; }
        .nav-principal a {
            text-decoration: none;
            color: var(--brand-yellow);
            font-weight: 700;
            font-size: 1.05rem;
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
        }

        .edit-container {
            max-width: 720px;
            margin: 3rem auto;
            padding: 2.5rem 3rem;
            background: #FFFFFF;
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border-top: 6px solid var(--brand-yellow);
        }

        .page-title {
            color: var(--brand-yellow);
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .avatar-preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .avatar-preview {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background-color: var(--brand-yellow);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--brand-yellow);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-preview svg {
            width: 60%;
            height: 60%;
            fill: #FFFFFF;
        }

        .file-upload-btn {
            background: #f4f4f4;
            border: 1px dashed #aaa;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .file-upload-btn:hover {
            background: #ececec;
            border-color: var(--brand-yellow);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #444;
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

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-submit {
            flex: 2;
            background: var(--brand-yellow);
            color: #FFFFFF;
            border: none;
            padding: 0.95rem;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--brand-yellow-hover);
        }

        .btn-cancel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #EAEAEA;
            color: #555;
            text-decoration: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #dedede;
        }

        .alert-box {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            text-align: center;
            font-weight: 600;
        }

        .alert-danger { background-color: #FDF2F2; color: #9B1C1C; }
        .alert-success { background-color: #F0FDF4; color: #166534; }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .edit-container { margin: 1.5rem; padding: 1.5rem; }
        }
    </style>
</head>
<body>

<header class="header-site">
    <div class="logo-container">
        <a href="index.php">
            <img src="../cliente/img_clientes/image.png" alt="COOPERATIVA AGROINTERRA" onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
            <div id="logo-fallback" style="display:none; align-items:center; gap:10px;">
                <div style="color:#EAA800; font-weight:800; font-size:1.1rem;">COOPERATIVA<br>AGROINTERRA</div>
            </div>
        </a>
    </div>

    <nav class="nav-principal">
        <a href="index.php">INICIO</a>
        <a href="catalogo.php">CATÁLOGO</a>
        <a href="usuario.php" class="user-pill"><i class="bi bi-person-fill"></i></a>
    </nav>
</header>

<main>
    <div class="edit-container">
        <h1 class="page-title">Editar Perfil</h1>

        <?php if (!empty($error)): ?>
            <div class="alert-box alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensaje)): ?>
            <div class="alert-box alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Selector y Previsualización de Foto -->
            <div class="avatar-preview-container">
                <div class="avatar-preview" id="preview-box">
                    <?php if (!empty($cliente['foto_perfil']) && file_exists('../uploads/perfil/' . $cliente['foto_perfil'])): ?>
                        <img id="avatar-img" src="../uploads/perfil/<?= htmlspecialchars($cliente['foto_perfil']) ?>" alt="Foto actual">
                    <?php else: ?>
                        <img id="avatar-img" style="display:none;" alt="Foto actual">
                        <svg id="avatar-svg" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4.2"/>
                            <path d="M12 13.5c-4.4 0-8 2.2-8 5v1.5h16v-1.5c0-2.8-3.6-5-8-5z"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <label class="file-upload-btn">
                    <i class="bi bi-camera-fill"></i> Cambiar foto
                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" style="display: none;">
                </label>
            </div>

            <!-- Datos Personales -->
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($cliente['nombre_cliente'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email_cliente'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Teléfono Celular</label>
                    <input type="tel" name="celular_cliente" value="<?= htmlspecialchars($cliente['celular_cliente'] ?? '') ?>" required>
                </div>

                <div class="form-group full-width">
                    <label>Dirección</label>
                    <input type="text" name="direccion_cliente" value="<?= htmlspecialchars($cliente['direccion_cliente'] ?? '') ?>">
                </div>

                <div class="form-group full-width">
                    <label>Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                    <input type="password" name="password_nueva" placeholder="Mínimo 6 caracteres">
                </div>
            </div>

            <div class="btn-group">
                <a href="usuario.php" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </div>
        </form>
    </div>
</main>

<script>
// Previsualización inmediata de la imagen seleccionada
document.getElementById('foto_perfil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('avatar-img');
            const svg = document.getElementById('avatar-svg');
            
            img.src = event.target.result;
            img.style.display = 'block';
            if (svg) svg.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>