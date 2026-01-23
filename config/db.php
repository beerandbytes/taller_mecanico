<?php
// config/db.php
// This file provides the same PDO connection as database.php but with better error handling
// Both files now use environment variables for configuration

// Use environment variables with fallbacks
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'trabajo_final_php';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Log error internally
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Show generic error message
    die("
        <div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ffcccc; background-color: #fff0f0; border-radius: 8px;'>
            <h2 style='color: #cc0000; margin-top: 0;'>Error de Sistema</h2>
            <p>Ha ocurrido un error al conectar con el servicio. Por favor inténtalo más tarde.</p>
        </div>
    ");
}
// End of file
