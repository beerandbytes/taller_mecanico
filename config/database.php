<?php
// config/database.php - Unified Database Configuration
// Supports environment variables, Docker, and local development

// Load environment variables if .env exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Determine environment and set defaults
$is_production = false;
if (isset($_SERVER['HTTP_HOST'])) {
    if (strpos($_SERVER['HTTP_HOST'], 'infinityfree') !== false || 
        strpos($_SERVER['HTTP_HOST'], 'tallermecanico') !== false) {
        $is_production = true;
    }
}

// Database configuration with proper fallbacks
if ($is_production) {
    // Production settings
    $host = getenv('DB_HOST') ?: 'sql208.infinityfree.com';
    $db   = getenv('DB_NAME') ?: 'if0_40685841_trabajo_final_php';
    $user = getenv('DB_USER') ?: 'if0_40685841';
    $pass = getenv('DB_PASS') ?: '';
} else {
    // Local development settings
    $host = getenv('DB_HOST') ?: 'localhost';
    $db   = getenv('DB_NAME') ?: 'trabajo_final_php';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
}

$charset = 'utf8mb4';

// Support host:port (common in local .env) and/or DB_PORT
$port = getenv('DB_PORT') ?: '';
if (strpos($host, ':') !== false && strpos($host, ']') === false) {
    $parts = explode(':', $host);
    if (count($parts) >= 2) {
        $maybePort = end($parts);
        if ($maybePort !== '' && ctype_digit($maybePort)) {
            $port = $port ?: $maybePort;
            array_pop($parts);
            $host = implode(':', $parts);
        }
    }
}

// Create DSN and options
$dsn = "mysql:host=$host;" . (!empty($port) ? "port=$port;" : "") . "dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => true, // For better performance
];

// Global PDO instance
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Test connection with a simple query
    $pdo->query("SELECT 1");
    
} catch (PDOException $e) {
    // Log the error for debugging
    error_log("Database Connection Error: " . $e->getMessage());
    error_log("DSN: $dsn, User: $user");
    
    // Provide user-friendly error message
    if ($is_production) {
        die("
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ffcccc; background-color: #fff0f0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #cc0000; margin-top: 0;'>Error de Conexión</h2>
                <p>Lo sentimos, no se puede conectar al servicio en este momento. Por favor, inténtalo más tarde.</p>
                <p style='font-size: 0.9em; color: #666;'>Si el problema persiste, contacta al administrador del sistema.</p>
            </div>
        ");
    } else {
        die("
            <div style='font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ffcccc; background-color: #fff0f0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #cc0000; margin-top: 0;'>Error de Conexión a la Base de Datos</h2>
                <p><strong>Detalles del error:</strong></p>
                <pre style='background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9em;'>{$e->getMessage()}</pre>
                <p><strong>Configuración intentada:</strong></p>
                <ul style='background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9em;'>
                    <li>Host: $host</li>
                    <li>Base de datos: $db</li>
                    <li>Usuario: $user</li>
                    <li>Contraseña: " . (empty($pass) ? '[vacía]' : '[oculta]') . "</li>
                </ul>
                <p><strong>Soluciones comunes:</strong></p>
                <ul>
                    <li>Verifica que el servidor MySQL/MariaDB esté en ejecución</li>
                    <li>Comprueba las credenciales en el archivo <code>.env</code></li>
                    <li>Asegúrate de que la base de datos <code>$db</code> exista</li>
                    <li>Verifica que el usuario <code>$user</code> tenga permisos</li>
                </ul>
            </div>
        ");
    }
}

// Optional: Create a function to get the PDO instance for consistency
function getDatabaseConnection() {
    global $pdo;
    return $pdo;
}

// End of file
