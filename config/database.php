<?php

mysqli_report(MYSQLI_REPORT_OFF);

// Configure these values for your own MySQL database. Environment variables
// take priority; defaults are suitable for a standard local XAMPP install.
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
$database = getenv('DB_NAME') ?: 'fakestoreapi';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Database connection failed. Check config/database.php settings and confirm the database exists.'
    ]);
    exit;
}

$conn->set_charset('utf8mb4');
