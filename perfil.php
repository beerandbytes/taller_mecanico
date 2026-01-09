<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
iniciarSesion();

// Verificar que el usuario esté logueado
if (!verificarSesion()) {
    header("Location: login.php");
    exit();
}

$tituloPagina = "Mi Perfil";
require_once __DIR__ . '/includes/header.php';

$errores = [];
$mensajeExito = '';
$usuarioActual = obtenerUsuarioActual();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = sanitizarDatos($_POST);
    
    // Si es actualización de datos personales
    if (isset($datos['actualizar_perfil'])) {
        $camposObligatorios = ['nombre', 'apellidos', 'email', 'telefono', 'fecha_de_nacimiento', 'sexo'];
        $errores = validarCamposObligatorios($datos, $camposObligatorios);
        
        // Validar email
        if (!empty($datos['email']) && !validarEmail($datos['email'])) {
            $errores[] = "El email no es válido";
        }
        
        // Validar email único (excepto el del usuario actual)
        if (empty($errores) && !empty($datos['email'])) {
            try {
                $stmt = $pdo->prepare("SELECT idUser FROM users_data WHERE email = ? AND idUser != ?");
                $stmt->execute([$datos['email'], $_SESSION['idUser']]);
                if ($stmt->fetch()) {
                    $errores[] = "El email ya está registrado por otro usuario";
                }
            } catch (PDOException $e) {
                $errores[] = "Error al verificar el email";
            }
        }
        
        if (empty($errores)) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE users_data 
                    SET nombre = ?, apellidos = ?, email = ?, telefono = ?, 
                        fecha_de_nacimiento = ?, direccion = ?, sexo = ?
                    WHERE idUser = ?
                ");
                $stmt->execute([
                    $datos['nombre'],
                    $datos['apellidos'],
                    $datos['email'],
                    $datos['telefono'],
                    $datos['fecha_de_nacimiento'],
                    $datos['direccion'] ?? null,
                    $datos['sexo'],
                    $_SESSION['idUser']
                ]);
                
                $mensajeExito = "Perfil actualizado correctamente";
                $usuarioActual = obtenerUsuarioActual(); // Actualizar datos
            } catch (PDOException $e) {
                $errores[] = "Error al actualizar el perfil";
            }
        }
    }
    
    // Si es cambio de contraseña
    if (isset($datos['cambiar_password'])) {
        if (empty($datos['nueva_password']) || empty($datos['confirmar_password'])) {
            $errores[] = "Todos los campos de contraseña son obligatorios";
        } elseif ($datos['nueva_password'] !== $datos['confirmar_password']) {
            $errores[] = "Las contraseñas no coinciden";
        } elseif (strlen($datos['nueva_password']) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        } else {
            try {
                $passwordHash = password_hash($datos['nueva_password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users_login SET password = ? WHERE idUser = ?");
                $stmt->execute([$passwordHash, $_SESSION['idUser']]);
                $mensajeExito = "Contraseña actualizada correctamente";
            } catch (PDOException $e) {
                $errores[] = "Error al actualizar la contraseña";
            }
        }
    }
}
?>

<h1>Mi Perfil</h1>

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

<?php if ($usuarioActual): ?>
    <div class="perfil-container">
        <section class="perfil-datos">
            <h2>Datos Personales</h2>
            <form method="POST" action="" class="form-perfil">
                <input type="hidden" name="actualizar_perfil" value="1">
                
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" value="<?php echo htmlspecialchars($usuarioActual['usuario']); ?>" disabled>
                    <small>El nombre de usuario no se puede cambiar</small>
                </div>
                
                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" required
                           value="<?php echo htmlspecialchars($usuarioActual['nombre']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="apellidos">Apellidos *</label>
                    <input type="text" id="apellidos" name="apellidos" required
                           value="<?php echo htmlspecialchars($usuarioActual['apellidos']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo htmlspecialchars($usuarioActual['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono *</label>
                    <input type="text" id="telefono" name="telefono" required
                           value="<?php echo htmlspecialchars($usuarioActual['telefono']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="fecha_de_nacimiento">Fecha de Nacimiento *</label>
                    <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" required
                           value="<?php echo htmlspecialchars($usuarioActual['fecha_de_nacimiento']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuarioActual['direccion'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="sexo">Sexo *</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Masculino" <?php echo ($usuarioActual['sexo'] === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($usuarioActual['sexo'] === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo ($usuarioActual['sexo'] === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                </div>
            </form>
        </section>
        
        <section class="perfil-password">
            <h2>Cambiar Contraseña</h2>
            <form method="POST" action="" class="form-password">
                <input type="hidden" name="cambiar_password" value="1">
                
                <div class="form-group">
                    <label for="nueva_password">Nueva Contraseña *</label>
                    <input type="password" id="nueva_password" name="nueva_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_password">Confirmar Nueva Contraseña *</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                </div>
            </form>
        </section>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

