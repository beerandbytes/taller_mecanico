<?php
/**
 * Script auxiliar para generar hash de contraseña
 * Ejecutar desde línea de comandos: php generate_password_hash.php
 * 
 * Este script genera un hash válido para usar en la base de datos
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña: $password\n";
echo "Hash generado: $hash\n\n";
echo "Para usar este hash en database.sql, reemplaza la línea:\n";
echo "VALUES (1, 'admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');\n";
echo "Por:\n";
echo "VALUES (1, 'admin', '$hash', 'admin');\n";

