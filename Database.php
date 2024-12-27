<?php
// Database.php
class Database {
    protected $pdo;
    protected static $instance;

    protected function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            error_log("Attempting connection with DSN: " . $dsn);

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
            error_log("PDO connection established successfully");
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function instance() {
        if (self::$instance === null) {
            error_log("Creating new Database instance");
            self::$instance = new self;
        }
        if (!self::$instance->pdo) {
            error_log("PDO object is null!");
            throw new Exception("PDO object is null!");
        }
        return self::$instance->pdo;
    }

    // Prevent cloning of the instance
    protected function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}