<?php
$tituloPagina = "Inicio";
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <h1>Bienvenido a Nuestro Sitio Web</h1>
    <p>Este es un sitio web desarrollado con PHP y MySQL como trabajo final del módulo.</p>
</section>

<section class="features">
    <h2>Características del Sitio</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <h3>Gestión de Usuarios</h3>
            <p>Sistema completo de registro e inicio de sesión con diferentes roles de usuario.</p>
        </div>
        <div class="feature-card">
            <h3>Noticias</h3>
            <p>Consulta las últimas noticias publicadas por nuestros administradores.</p>
            <a href="noticias.php" class="btn">Ver Noticias</a>
        </div>
        <div class="feature-card">
            <h3>Gestión de Citas</h3>
            <p>Los usuarios registrados pueden solicitar y gestionar sus citas.</p>
        </div>
    </div>
</section>

<section class="about">
    <h2>Sobre Nosotros</h2>
    <p>Este sitio web ha sido desarrollado utilizando las tecnologías más modernas:</p>
    <ul>
        <li><strong>HTML5</strong> para la estructura</li>
        <li><strong>CSS3</strong> para el diseño</li>
        <li><strong>JavaScript</strong> para la interactividad</li>
        <li><strong>PHP</strong> para el backend</li>
        <li><strong>MySQL</strong> para la base de datos</li>
    </ul>
    <?php if (file_exists('assets/images/placeholder.jpg')): ?>
        <img src="assets/images/placeholder.jpg" alt="Tecnologías web" class="about-image">
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

