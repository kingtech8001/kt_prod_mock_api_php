<?php

require_once "../config/database.php";

header("Content-Type: application/json; charset=UTF-8");


/*
|--------------------------------------------------------------------------
| ONLY POST
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "message" => "Method not allowed"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| READ JSON BODY
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents("php://input");

$data = json_decode($rawInput, true);


/*
|--------------------------------------------------------------------------
| CHECK JSON
|--------------------------------------------------------------------------
*/

if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "message" => "Invalid JSON"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| GET INPUT
|--------------------------------------------------------------------------
*/

$username = isset($data["username"])
    ? trim($data["username"])
    : "";

$email = isset($data["email"])
    ? trim($data["email"])
    : "";

$password = isset($data["password"])
    ? (string)$data["password"]
    : "";


/*
|--------------------------------------------------------------------------
| VALIDATE REQUIRED FIELDS
|--------------------------------------------------------------------------
*/

if (
    $username === "" ||
    $email === "" ||
    $password === ""
) {

    http_response_code(400);

    echo json_encode([
        "message" => "Username, email and password are required"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDATE EMAIL
|--------------------------------------------------------------------------
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(400);

    echo json_encode([
        "message" => "Invalid email"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| CHECK EXISTING EMAIL
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email = ? LIMIT 1"
);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "message" => "Database query failed"
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);


mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$existingUser = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if ($existingUser) {

    http_response_code(409);

    echo json_encode([
        "message" => "Email already registered"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| HASH PASSWORD
|--------------------------------------------------------------------------
*/

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
|--------------------------------------------------------------------------
| INSERT USER
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users
        (username, email, password, role)
     VALUES
        (?, ?, ?, 'user')"
);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "message" => "Failed to prepare registration query",
        "error" => mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $username,
    $email,
    $hashedPassword
);


if (!mysqli_stmt_execute($stmt)) {

    http_response_code(500);

    echo json_encode([
        "message" => "Registration failed",
        "error" => mysqli_stmt_error($stmt)
    ]);

    mysqli_stmt_close($stmt);

    exit;
}


$userId = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

http_response_code(201);

echo json_encode([
    "message" => "Registration successful",
    "userId" => (int)$userId,
    "username" => $username,
    "email" => $email,
    "role" => "user"
], JSON_PRETTY_PRINT);

exit;

?>