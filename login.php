<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
iniciarSesion();

// Si ya está logueado, redirigir al index
if (verificarSesion()) {
    header("Location: index.php");
    exit();
}

$tituloPagina = "Iniciar Sesión";
require_once __DIR__ . '/includes/header.php';

$errores = [];
$mensajeExito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($password)) {
        $errores[] = "Usuario y contraseña son obligatorios";
    } else {
        try {
            // Buscar usuario en users_login con JOIN a users_data
            $stmt = $pdo->prepare("
                SELECT ul.*, ud.nombre, ud.apellidos 
                FROM users_login ul
                INNER JOIN users_data ud ON ul.idUser = ud.idUser
                WHERE ul.usuario = ?
            ");
            $stmt->execute([$usuario]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['idUser'] = $user['idUser'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                
                $mensajeExito = "Inicio de sesión exitoso. Serás redirigido al inicio.";
                header("Refresh: 2; url=index.php");
            } else {
                $errores[] = "Usuario o contraseña incorrectos";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al verificar las credenciales";
        }
    }
}
?>

<h1>Iniciar Sesión</h1>

<?php if (!empty($errores)): ?>
    <div class="mensaje error">
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($mensajeExito): ?>
    <div class="mensaje exito">
        <?php echo htmlspecialchars($mensajeExito); ?>
    </div>
<?php endif; ?>

<form method="POST" action="" class="form-login">
    <div class="form-group">
        <label for="usuario">Usuario *</label>
        <input type="text" id="usuario" name="usuario" required
               value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="password">Contraseña *</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        <a href="registro.php" class="btn btn-secondary">¿No tienes cuenta? Regístrate</a>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

