<?php
$servername = getenv("DB_HOST") ?: "mysql";
$username   = getenv("DB_USER") ?: "root";
$password   = getenv("DB_PASS") ?: "secret";
$dbname     = getenv("DB_NAME") ?: "inventory";

// Create connection
$conn = @new mysqli($servername, $username, $password, $dbname);

// If connection fails, throw an error instead of dying silently
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    $conn = null; // Make sure $conn exists but is null
}
?>
