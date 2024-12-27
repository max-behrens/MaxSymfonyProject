<?php
// test-connection.php
require_once 'init.php';

try {
    error_log("Starting connection test");

    // Test direct PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    error_log("Testing direct PDO connection with DSN: " . $dsn);
    $directPdo = new PDO($dsn, DB_USER, DB_PASS);
    echo "Direct PDO connection successful<br>";

    // Test Database singleton
    error_log("Testing Database singleton");
    $db = Database::instance();
    echo "Database singleton connection successful<br>";

    // Test User class
    error_log("Testing User class");
    $user = new User();
    echo "User class instantiated successfully<br>";

    // Test query
    error_log("Testing simple query");
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Available tables:<br>";
    print_r($tables);

} catch (Exception $e) {
    error_log("Test failed: " . $e->getMessage());
    echo "Test failed: " . $e->getMessage();
}