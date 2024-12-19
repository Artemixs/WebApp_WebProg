<?php
$host = "localhost";      // Database host (default for XAMPP is "localhost")
$user = "root";           // Database username (default for XAMPP is "root")
$password = "";           // Database password (default is empty for XAMPP)
$dbname = "foodiebayan";  // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
