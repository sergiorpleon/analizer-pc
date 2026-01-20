<?php

$autoloadFile = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadFile)) {
    echo "Error: vendor/autoload.php not found at $autoloadFile\n";
    exit(1);
}

require_once $autoloadFile;

echo "Checking for src/Models/Database.php...\n";
$classFile = __DIR__ . '/../src/Models/Database.php';
if (file_exists($classFile)) {
    echo "File found at $classFile\n";
} else {
    echo "File NOT found at $classFile\n";
    echo "Current directory: " . getcwd() . "\n";
    echo "Directory listing of ..:\n";
    print_r(scandir(__DIR__ . '/..'));
}

use App\Models\Database;

try {
    echo "Initializing database...\n";
    if (!class_exists(Database::class)) {
        echo "Error: Class App\Models\Database still not found after loading autoloader.\n";
        exit(1);
    }
    $db = Database::getInstance();
    $db->initializeTable();
    echo "Database initialized successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
