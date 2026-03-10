<?php
require_once 'config/database.php';
$stmt = $pdo->query("SELECT * FROM consejos");
$res = $stmt->fetchAll();
print_r($res);
