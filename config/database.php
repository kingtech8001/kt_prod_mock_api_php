<?php

$host = "localhost";
$user = "u360186299_u360186299";
$password = "Mockapi@123";
$database = "u360186299_u360186299";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);

    echo json_encode([
        "error" => "Database connection failed"
    ]);

    exit;
}

<<<<<<< HEAD
$conn->set_charset("utf8mb4");
=======
$conn->set_charset("utf8mb4");
>>>>>>> 66a0892 (deploy)
