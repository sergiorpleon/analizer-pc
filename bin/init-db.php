<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Database;

try {
    echo "Initializing database...\n";
    $db = Database::getInstance();
    $db->initializeTable();
    echo "Database initialized successfully!!!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
