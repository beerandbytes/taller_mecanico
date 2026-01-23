<?php
// Configuration file - Use environment variables for sensitive data
// For production, set these via environment variables or .env file
// Never commit actual credentials to version control

$is_production = false;
if (isset($_SERVER['HTTP_HOST'])) {
    if (strpos($_SERVER['HTTP_HOST'], 'infinityfree') !== false || strpos($_SERVER['HTTP_HOST'], 'tallermecanico') !== false) {
        $is_production = true;
    }
}

if ($is_production) {
    // Production Credentials - Use environment variables
    return [
        'DB_HOST' => getenv('DB_HOST') ?: 'sql208.infinityfree.com',
        'DB_NAME' => getenv('DB_NAME') ?: 'if0_40685841_trabajo_final_php',
        'DB_USER' => getenv('DB_USER') ?: 'if0_40685841',
        'DB_PASS' => getenv('DB_PASS') ?: '', // MUST be set via environment variable
        'DB_CHARSET' => 'utf8mb4'
    ];
} else {
    // Localhost Credentials - Use environment variables with safe defaults
    return [
        'DB_HOST' => getenv('DB_HOST') ?: 'localhost',
        'DB_NAME' => getenv('DB_NAME') ?: 'trabajo_final_php',
        'DB_USER' => getenv('DB_USER') ?: 'root',
        'DB_PASS' => getenv('DB_PASS') ?: '',
        'DB_CHARSET' => 'utf8mb4'
    ];
}
