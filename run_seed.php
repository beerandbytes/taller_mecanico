<?php
require __DIR__ . '/config/database.php';
$sql = file_get_contents(__DIR__ . '/database/seed_consejos.sql');
try {
    $pdo->exec($sql);
    echo "Seed executed successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
