<?php
require_once 'config/database.php';
try {
    $stmt = $pdo->query("DESCRIBE consejos");
    $columns = $stmt->fetchAll();
    echo "Columns in 'consejos' table:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
