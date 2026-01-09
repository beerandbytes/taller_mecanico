<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
iniciarSesion();

$tituloPagina = "Noticias";
require_once __DIR__ . '/includes/header.php';

// Obtener todas las noticias con datos del autor
try {
    $stmt = $pdo->query("
        SELECT n.*, CONCAT(ud.nombre, ' ', ud.apellidos) AS autor_nombre
        FROM noticias n
        INNER JOIN users_data ud ON n.idUser = ud.idUser
        ORDER BY n.fecha DESC, n.idNoticia DESC
    ");
    $noticias = $stmt->fetchAll();
} catch (PDOException $e) {
    $noticias = [];
    $error = "Error al cargar las noticias.";
}
?>

<h1>Noticias</h1>

<?php if (isset($error)): ?>
    <div class="mensaje error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($noticias)): ?>
    <div class="mensaje info">
        <p>No hay noticias disponibles en este momento.</p>
    </div>
<?php else: ?>
    <div class="noticias-container">
        <?php foreach ($noticias as $noticia): ?>
            <article class="noticia-card">
                <?php if (!empty($noticia['imagen']) && file_exists($noticia['imagen'])): ?>
                    <div class="noticia-imagen">
                        <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                    </div>
                <?php endif; ?>
                <div class="noticia-contenido">
                    <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                    <div class="noticia-meta">
                        <span class="noticia-fecha">Fecha: <?php echo date('d/m/Y', strtotime($noticia['fecha'])); ?></span>
                        <span class="noticia-autor">Autor: <?php echo htmlspecialchars($noticia['autor_nombre']); ?></span>
                    </div>
                    <div class="noticia-texto">
                        <?php echo nl2br(htmlspecialchars($noticia['texto'])); ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

