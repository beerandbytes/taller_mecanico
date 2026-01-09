<?php
// Funciones auxiliares del sistema

require_once __DIR__ . '/../config/database.php';

/**
 * Valida un email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida campos obligatorios en un array
 */
function validarCamposObligatorios($datos, $camposObligatorios) {
    $errores = [];
    foreach ($camposObligatorios as $campo) {
        if (empty($datos[$campo])) {
            $errores[] = "El campo $campo es obligatorio";
        }
    }
    return $errores;
}

/**
 * Sanitiza datos de entrada
 */
function sanitizarDatos($datos) {
    $sanitizados = [];
    foreach ($datos as $clave => $valor) {
        if (is_string($valor)) {
            $sanitizados[$clave] = trim(strip_tags($valor));
        } else {
            $sanitizados[$clave] = $valor;
        }
    }
    return $sanitizados;
}

/**
 * Verifica si hay una sesión activa
 */
function verificarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['idUser']) && isset($_SESSION['rol']);
}

/**
 * Verifica si el usuario tiene un rol específico
 */
function verificarRol($rolRequerido) {
    if (!verificarSesion()) {
        return false;
    }
    return $_SESSION['rol'] === $rolRequerido;
}

/**
 * Obtiene los datos del usuario actual
 */
function obtenerUsuarioActual() {
    global $pdo;
    
    if (!verificarSesion()) {
        return null;
    }
    
    $idUser = $_SESSION['idUser'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT ud.*, ul.usuario, ul.rol 
            FROM users_data ud 
            INNER JOIN users_login ul ON ud.idUser = ul.idUser 
            WHERE ud.idUser = ?
        ");
        $stmt->execute([$idUser]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Inicia sesión si no está iniciada
 */
function iniciarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
?>

