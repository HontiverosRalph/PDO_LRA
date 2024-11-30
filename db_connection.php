<?php
$host = 'localhost';
$user = 'root';  // Change to your database username
$password = '';  // Change to your database password
$database = 'job_application_system';  // Your database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
