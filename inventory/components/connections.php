<?php
$servername = getenv("DB_HOST") ?: "mysql";
$username   = getenv("DB_USER") ?: "root";
$password   = getenv("DB_PASS") ?: "secret";
$dbname     = getenv("DB_NAME") ?: "inventory";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
