<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Testing Database Connection...\n";

try {
    $dbPath = __DIR__ . '/../config/database.php';
    if (!file_exists($dbPath)) {
        throw new Exception("Database file not found at $dbPath");
    }
    require_once $dbPath;

    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "Database connection successful!\n";
    } else {
        echo "Database connection returned null.\n";
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
