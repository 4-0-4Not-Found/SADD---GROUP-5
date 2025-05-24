<?php
$host = 'localhost';
$dbname = 'attendance'; 
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn){
    header('Content-Type: application/json');
    die(json_encode([
        'error' => 'Database connection failed',
        'message' => mysqli_connect_error()
    ]));
}

// Set charset to prevent encoding issues
mysqli_set_charset($conn, 'utf8mb4');
?>