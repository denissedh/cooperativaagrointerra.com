<?php 
require 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooperativa Agrointerra — ¿Quiénes Somos?</title>
    <link rel="stylesheet" href="estilo_cliente.css">
</head>
<body>

<!-- ENCABEZADO -->
<header class="header-site">
    <div class="logo-container">
        <a href="index.php"><img src="img_clientes/image.png" alt="Agrointerra"></a>
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

<main class="quienes-main-container">
    <div class="quienes-info">
        <img src="img_clientes/image.png" alt="Agrointerra Logo" class="logo-cuerpo">
        
        <h1>¿QUIÉNES SOMOS?</h1>
        
        <p class="descripcion">
            Una empresa del municipio de Ascensión Chihuahua, dedicada a la creación y comercialización de fertilizantes, plaguicidas y semillas para siembra, todo esto por mayoreo.
        </p>

        <div class="tab-buttons">
            <button class="btn-pildora" onclick="cambiarTab('mision', 0, this)">MISIÓN</button>
            <button class="btn-pildora" onclick="cambiarTab('vision', 1, this)">VISIÓN</button>
            <button class="btn-pildora" onclick="cambiarTab('valores', 2, this)">VALORES</button>
        </div>

        <div id="mision" class="info-bocadillo">
            <p>Ofrecer productos fertilizantes para agricultura orgánica a los campos agrícolas de la región noroeste del estado de Chihuahua y el resto de México, a precios competitivos en favor a la economía social.</p>
        </div>
        <div id="vision" class="info-bocadillo">
            <p>Ser una cooperativa líder en la creación de fuentes de empleo a través de la generación de programas de trabajo en función de la economía social envuelto en un entorno integral de fertilizantes para agricultura orgánica.</p>
        </div>
        <div id="valores" class="info-bocadillo">
            <p> Calidad moral, honestidad, responsabilidad, ética, respeto, integridad, lealtad, democracia, confianza, equidad, justicia, adaptabilidad, empatía, trabajo en equipo y diferencia</p>
        </div>
    </div>

    <div class="quienes-galeria-container">
        <div class="flor-fondo"></div>
        <div class="cards-wrapper">
            <div class="card card-left" id="card-0">
                <img src="img_clientes/a.png" alt="Misión">
            </div>
            <div class="card card-center" id="card-1">
                <img src="img_clientes/g.png" alt="Visión">
            </div>
            <div class="card card-right" id="card-2">
                <img src="img_clientes/e.png" alt="Valores">
            </div>
        </div>
    </div>
</main>

<script>
function cambiarTab(id, indice, botonSeleccionado) {
    document.querySelectorAll('.info-bocadillo').forEach(el => el.classList.remove('activo'));
    document.querySelectorAll('.btn-pildora').forEach(btn => btn.classList.remove('activo'));

    document.getElementById(id).classList.add('activo');
    if (botonSeleccionado) botonSeleccionado.classList.add('activo');

    const cards = [document.getElementById('card-0'), document.getElementById('card-1'), document.getElementById('card-2')];
    
    cards.forEach(card => card.className = 'card'); 

    if (indice === 0) {
        cards[0].classList.add('card-center');
        cards[1].classList.add('card-right');
        cards[2].classList.add('card-left');
    } else if (indice === 1) {
        cards[0].classList.add('card-left');
        cards[1].classList.add('card-center');
        cards[2].classList.add('card-right');
    } else {
        cards[0].classList.add('card-right');
        cards[1].classList.add('card-left');
        cards[2].classList.add('card-center');
    }
}
</script>

</body>
</html>