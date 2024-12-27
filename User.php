<?php
// User.php
class User {
    protected $db;

    public function __construct() {
        try {
            error_log("Initializing User class");
            $this->db = Database::instance();
            if (!$this->db) {
                error_log("Database instance is null in User constructor");
                throw new Exception("Database instance is null in User constructor");
            }
            error_log("Database instance successfully obtained in User constructor");
        } catch (Exception $e) {
            error_log("Error in User constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function emailExists($email) {
        try {
            if (!$this->db) {
                error_log("Database connection is null in emailExists");
                throw new Exception("Database connection is null");
            }

            error_log("Preparing statement for email: " . $email);
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");

            if (!$stmt) {
                error_log("Statement preparation failed");
                throw new Exception("Failed to prepare statement");
            }

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch();
            error_log("Query executed successfully. Result: " . ($result ? "User found" : "No user found"));

            return $result;
        } catch (Exception $e) {
            error_log("Error in emailExists: " . $e->getMessage());
            throw $e;
        }
    }

    public function hash($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function updatePasswordHash($userId, $newHash) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE userId = :userId");
            $stmt->bindParam(':password', $newHash, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update password hash: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $stmt = $this->db->prepare("SELECT userId, fullName, email FROM users ORDER BY userId");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Failed to fetch users: " . $e->getMessage());
            return [];
        }
    }




}