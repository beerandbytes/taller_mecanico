<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
iniciarSesion();

// Verificar que el usuario esté logueado
if (!verificarSesion()) {
    header("Location: login.php");
    exit();
}

$tituloPagina = "Mis Citaciones";
require_once __DIR__ . '/includes/header.php';

$errores = [];
$mensajeExito = '';
$idCitaEditar = null;
$citaEditar = null;

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = sanitizarDatos($_POST);
    
    // Crear nueva cita
    if (isset($datos['crear_cita'])) {
        $camposObligatorios = ['fecha_cita', 'motivo_cita'];
        $errores = validarCamposObligatorios($datos, $camposObligatorios);
        
        // Validar que la fecha no sea anterior a hoy
        if (empty($errores) && !empty($datos['fecha_cita'])) {
            $fechaCita = strtotime($datos['fecha_cita']);
            $fechaHoy = strtotime(date('Y-m-d'));
            if ($fechaCita < $fechaHoy) {
                $errores[] = "La fecha de la cita no puede ser anterior a hoy";
            }
        }
        
        if (empty($errores)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)");
                $stmt->execute([$_SESSION['idUser'], $datos['fecha_cita'], $datos['motivo_cita']]);
                $mensajeExito = "Cita creada correctamente";
            } catch (PDOException $e) {
                $errores[] = "Error al crear la cita";
            }
        }
    }
    
    // Actualizar cita
    if (isset($datos['actualizar_cita'])) {
        $idCita = intval($datos['idCita']);
        $camposObligatorios = ['fecha_cita', 'motivo_cita'];
        $errores = validarCamposObligatorios($datos, $camposObligatorios);
        
        // Validar que la fecha no sea anterior a hoy
        if (empty($errores) && !empty($datos['fecha_cita'])) {
            $fechaCita = strtotime($datos['fecha_cita']);
            $fechaHoy = strtotime(date('Y-m-d'));
            if ($fechaCita < $fechaHoy) {
                $errores[] = "La fecha de la cita no puede ser anterior a hoy";
            }
        }
        
        // Verificar que la cita pertenece al usuario
        if (empty($errores)) {
            try {
                $stmt = $pdo->prepare("SELECT idCita FROM citas WHERE idCita = ? AND idUser = ?");
                $stmt->execute([$idCita, $_SESSION['idUser']]);
                if (!$stmt->fetch()) {
                    $errores[] = "No tienes permiso para modificar esta cita";
                }
            } catch (PDOException $e) {
                $errores[] = "Error al verificar la cita";
            }
        }
        
        if (empty($errores)) {
            try {
                $stmt = $pdo->prepare("UPDATE citas SET fecha_cita = ?, motivo_cita = ? WHERE idCita = ? AND idUser = ?");
                $stmt->execute([$datos['fecha_cita'], $datos['motivo_cita'], $idCita, $_SESSION['idUser']]);
                $mensajeExito = "Cita actualizada correctamente";
            } catch (PDOException $e) {
                $errores[] = "Error al actualizar la cita";
            }
        }
    }
}

// Eliminar cita
if (isset($_GET['eliminar'])) {
    $idCita = intval($_GET['eliminar']);
    
    try {
        // Verificar que la cita pertenece al usuario y que la fecha >= hoy
        $stmt = $pdo->prepare("SELECT fecha_cita FROM citas WHERE idCita = ? AND idUser = ?");
        $stmt->execute([$idCita, $_SESSION['idUser']]);
        $cita = $stmt->fetch();
        
        if ($cita) {
            $fechaCita = strtotime($cita['fecha_cita']);
            $fechaHoy = strtotime(date('Y-m-d'));
            
            if ($fechaCita >= $fechaHoy) {
                $stmt = $pdo->prepare("DELETE FROM citas WHERE idCita = ? AND idUser = ?");
                $stmt->execute([$idCita, $_SESSION['idUser']]);
                $mensajeExito = "Cita eliminada correctamente";
            } else {
                $errores[] = "No se pueden eliminar citas pasadas";
            }
        } else {
            $errores[] = "No tienes permiso para eliminar esta cita";
        }
    } catch (PDOException $e) {
        $errores[] = "Error al eliminar la cita";
    }
}

// Obtener cita para editar
if (isset($_GET['editar'])) {
    $idCitaEditar = intval($_GET['editar']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM citas WHERE idCita = ? AND idUser = ?");
        $stmt->execute([$idCitaEditar, $_SESSION['idUser']]);
        $citaEditar = $stmt->fetch();
        
        if (!$citaEditar) {
            $errores[] = "No tienes permiso para editar esta cita";
            $idCitaEditar = null;
        } else {
            // Validar que la fecha no sea anterior a hoy
            $fechaCita = strtotime($citaEditar['fecha_cita']);
            $fechaHoy = strtotime(date('Y-m-d'));
            if ($fechaCita < $fechaHoy) {
                $errores[] = "No se pueden modificar citas pasadas";
                $idCitaEditar = null;
                $citaEditar = null;
            }
        }
    } catch (PDOException $e) {
        $errores[] = "Error al obtener la cita";
    }
}

// Obtener todas las citas del usuario
try {
    $stmt = $pdo->prepare("SELECT * FROM citas WHERE idUser = ? ORDER BY fecha_cita ASC");
    $stmt->execute([$_SESSION['idUser']]);
    $citas = $stmt->fetchAll();
} catch (PDOException $e) {
    $citas = [];
    $errores[] = "Error al cargar las citas";
}
?>

<h1>Mis Citaciones</h1>

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

<section class="citas-formulario">
    <h2><?php echo $idCitaEditar ? 'Editar Cita' : 'Solicitar Nueva Cita'; ?></h2>
    <form method="POST" action="" class="form-cita">
        <?php if ($idCitaEditar): ?>
            <input type="hidden" name="actualizar_cita" value="1">
            <input type="hidden" name="idCita" value="<?php echo $idCitaEditar; ?>">
        <?php else: ?>
            <input type="hidden" name="crear_cita" value="1">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="fecha_cita">Fecha de la Cita *</label>
            <input type="date" id="fecha_cita" name="fecha_cita" required
                   min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo $citaEditar ? htmlspecialchars($citaEditar['fecha_cita']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="motivo_cita">Motivo de la Cita *</label>
            <textarea id="motivo_cita" name="motivo_cita" rows="4" required><?php echo $citaEditar ? htmlspecialchars($citaEditar['motivo_cita']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <?php echo $idCitaEditar ? 'Actualizar Cita' : 'Crear Cita'; ?>
            </button>
            <?php if ($idCitaEditar): ?>
                <a href="citaciones.php" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>
</section>

<section class="citas-lista">
    <h2>Mis Citas</h2>
    <?php if (empty($citas)): ?>
        <p>No tienes citas programadas.</p>
    <?php else: ?>
        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <?php
                    $fechaCita = strtotime($cita['fecha_cita']);
                    $fechaHoy = strtotime(date('Y-m-d'));
                    $puedeModificar = $fechaCita >= $fechaHoy;
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y', $fechaCita); ?></td>
                        <td><?php echo htmlspecialchars($cita['motivo_cita']); ?></td>
                        <td>
                            <?php if ($puedeModificar): ?>
                                <a href="?editar=<?php echo $cita['idCita']; ?>" class="btn btn-small">Editar</a>
                                <a href="?eliminar=<?php echo $cita['idCita']; ?>" class="btn btn-small btn-danger" 
                                   onclick="return confirm('¿Estás seguro de eliminar esta cita?');">Eliminar</a>
                            <?php else: ?>
                                <span class="text-muted">Cita pasada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

