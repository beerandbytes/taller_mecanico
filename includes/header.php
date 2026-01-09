<?php
require_once __DIR__ . '/functions.php';
iniciarSesion();

// Obtener la página actual
$paginaActual = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? htmlspecialchars($tituloPagina) : 'Trabajo Final PHP'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="nav-logo">Mi Sitio Web</a>
                <ul class="nav-menu">
                    <?php if (!verificarSesion()): ?>
                        <!-- Navegación para visitantes -->
                        <li><a href="index.php" class="<?php echo $paginaActual === 'index' ? 'active' : ''; ?>">Inicio</a></li>
                        <li><a href="noticias.php" class="<?php echo $paginaActual === 'noticias' ? 'active' : ''; ?>">Noticias</a></li>
                        <li><a href="registro.php" class="<?php echo $paginaActual === 'registro' ? 'active' : ''; ?>">Registro</a></li>
                        <li><a href="login.php" class="<?php echo $paginaActual === 'login' ? 'active' : ''; ?>">Iniciar Sesión</a></li>
                    <?php elseif (verificarSesion() && isset($_SESSION['rol']) && $_SESSION['rol'] === 'user'): ?>
                        <!-- Navegación para usuarios -->
                        <li><a href="index.php" class="<?php echo $paginaActual === 'index' ? 'active' : ''; ?>">Inicio</a></li>
                        <li><a href="noticias.php" class="<?php echo $paginaActual === 'noticias' ? 'active' : ''; ?>">Noticias</a></li>
                        <li><a href="citaciones.php" class="<?php echo $paginaActual === 'citaciones' ? 'active' : ''; ?>">Citaciones</a></li>
                        <li><a href="perfil.php" class="<?php echo $paginaActual === 'perfil' ? 'active' : ''; ?>">Perfil</a></li>
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    <?php elseif (verificarSesion() && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <!-- Navegación para administradores -->
                        <li><a href="index.php" class="<?php echo $paginaActual === 'index' ? 'active' : ''; ?>">Inicio</a></li>
                        <li><a href="noticias.php" class="<?php echo $paginaActual === 'noticias' ? 'active' : ''; ?>">Noticias</a></li>
                        <li><a href="usuarios-administracion.php" class="<?php echo $paginaActual === 'usuarios-administracion' ? 'active' : ''; ?>">Usuarios</a></li>
                        <li><a href="citas-administracion.php" class="<?php echo $paginaActual === 'citas-administracion' ? 'active' : ''; ?>">Citas</a></li>
                        <li><a href="noticias-administracion.php" class="<?php echo $paginaActual === 'noticias-administracion' ? 'active' : ''; ?>">Noticias Admin</a></li>
                        <li><a href="perfil.php" class="<?php echo $paginaActual === 'perfil' ? 'active' : ''; ?>">Perfil</a></li>
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">

