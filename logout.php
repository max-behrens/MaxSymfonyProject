<?php
session_start();
session_destroy();

// Ensure BASE_URL is defined
define('BASE_URL', 'http://localhost:8080/'); // Replace with your actual base URL

// Redirect to login page
header("Location: " . BASE_URL . "login.php");
exit();