<?php
// scripts/update_schema_v2.php
require_once __DIR__ . '/../config/db.php';

try {
    echo "Updating schema...\n";

    // Add new columns if they don't exist
    $columns = [
        'calle' => 'TEXT',
        'codigo_postal' => 'VARCHAR(10)',
        'ciudad' => 'VARCHAR(100)',
        'provincia' => 'VARCHAR(100)'
    ];

    foreach ($columns as $col => $type) {
        try {
            $pdo->query("SELECT $col FROM users_data LIMIT 1");
            echo "Column $col already exists.\n";
        } catch (PDOException $e) {
            echo "Adding column $col...\n";
            if ($type === 'TEXT') {
                 $pdo->exec("ALTER TABLE users_data ADD COLUMN $col $type");
            } else {
                 $pdo->exec("ALTER TABLE users_data ADD COLUMN $col $type NOT NULL DEFAULT ''");
            }
        }
    }

    echo "Schema update completed successfully.\n";

} catch (PDOException $e) {
    die("Error updating schema: " . $e->getMessage() . "\n");
}
