<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
iniciarSesion();

$tituloPagina = "Registro";
require_once __DIR__ . '/includes/header.php';

$errores = [];
$mensajeExito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar datos
    $datos = sanitizarDatos($_POST);
    
    // Campos obligatorios
    $camposObligatorios = ['nombre', 'apellidos', 'email', 'telefono', 'fecha_de_nacimiento', 'sexo', 'usuario', 'password'];
    $errores = validarCamposObligatorios($datos, $camposObligatorios);
    
    // Validar email
    if (!empty($datos['email']) && !validarEmail($datos['email'])) {
        $errores[] = "El email no es válido";
    }
    
    // Validar que las contraseñas coincidan si se proporciona confirmación
    if (!empty($datos['password']) && isset($datos['password_confirm']) && $datos['password'] !== $datos['password_confirm']) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    // Validar email único
    if (empty($errores) && !empty($datos['email'])) {
        try {
            $stmt = $pdo->prepare("SELECT idUser FROM users_data WHERE email = ?");
            $stmt->execute([$datos['email']]);
            if ($stmt->fetch()) {
                $errores[] = "El email ya está registrado";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al verificar el email";
        }
    }
    
    // Validar usuario único
    if (empty($errores) && !empty($datos['usuario'])) {
        try {
            $stmt = $pdo->prepare("SELECT idLogin FROM users_login WHERE usuario = ?");
            $stmt->execute([$datos['usuario']]);
            if ($stmt->fetch()) {
                $errores[] = "El usuario ya está registrado";
            }
        } catch (PDOException $e) {
            $errores[] = "Error al verificar el usuario";
        }
    }
    
    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {
        try {
            $pdo->beginTransaction();
            
            // Insertar en users_data
            $stmt = $pdo->prepare("
                INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_de_nacimiento, direccion, sexo)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $datos['nombre'],
                $datos['apellidos'],
                $datos['email'],
                $datos['telefono'],
                $datos['fecha_de_nacimiento'],
                $datos['direccion'] ?? null,
                $datos['sexo']
            ]);
            
            $idUser = $pdo->lastInsertId();
            
            // Encriptar contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            // Insertar en users_login (rol siempre 'user' para nuevos registros)
            $stmt = $pdo->prepare("
                INSERT INTO users_login (idUser, usuario, password, rol)
                VALUES (?, ?, ?, 'user')
            ");
            $stmt->execute([
                $idUser,
                $datos['usuario'],
                $passwordHash
            ]);
            
            $pdo->commit();
            $mensajeExito = "Registro exitoso. Serás redirigido al inicio de sesión.";
            header("Refresh: 3; url=login.php");
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errores[] = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<h1>Registro de Usuario</h1>

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

<form method="POST" action="" class="form-registro">
    <h2>Datos Personales</h2>
    
    <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" required 
               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="apellidos">Apellidos *</label>
        <input type="text" id="apellidos" name="apellidos" required
               value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="telefono">Teléfono *</label>
        <input type="text" id="telefono" name="telefono" required
               value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="fecha_de_nacimiento">Fecha de Nacimiento *</label>
        <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" required
               value="<?php echo isset($_POST['fecha_de_nacimiento']) ? htmlspecialchars($_POST['fecha_de_nacimiento']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="sexo">Sexo *</label>
        <select id="sexo" name="sexo" required>
            <option value="">Seleccione...</option>
            <option value="Masculino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="Femenino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
            <option value="Otro" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] === 'Otro') ? 'selected' : ''; ?>>Otro</option>
        </select>
    </div>
    
    <h2>Datos de Inicio de Sesión</h2>
    
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
        <label for="password_confirm">Confirmar Contraseña *</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Registrarse</button>
        <a href="login.php" class="btn btn-secondary">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

