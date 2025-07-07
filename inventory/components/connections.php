<?php
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // your DB username
$password = "";            // your DB password ("" for default XAMPP)
$dbname = "inventory";     // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Uncomment below to confirm connection
//echo "Connected successfully!";
?>
