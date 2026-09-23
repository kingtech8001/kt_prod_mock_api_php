<?php

require_once "../config/database.php";
require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;

header("Content-Type: application/json; charset=UTF-8");

/*
|--------------------------------------------------------------------------
| Only POST allowed
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
| Read JSON body
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents("php://input");

$data = json_decode($rawInput, true);

if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "message" => "Invalid JSON"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Validate fields
|--------------------------------------------------------------------------
*/

$email = isset($data["email"])
    ? trim($data["email"])
    : "";

$password = isset($data["password"])
    ? (string)$data["password"]
    : "";


if ($email === "" || $password === "") {

    http_response_code(400);

    echo json_encode([
        "message" => "Email and password are required"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Find user
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        username,
        email,
        password,
        role
    FROM users
    WHERE LOWER(email) = LOWER(?)
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "message" => "Database query failed",
        "error" => mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);


if (!mysqli_stmt_execute($stmt)) {

    http_response_code(500);

    echo json_encode([
        "message" => "Database query execution failed",
        "error" => mysqli_stmt_error($stmt)
    ]);

    mysqli_stmt_close($stmt);

    exit;
}


$result = mysqli_stmt_get_result($stmt);

if (!$result) {

    http_response_code(500);

    echo json_encode([
        "message" => "Could not read database result"
    ]);

    mysqli_stmt_close($stmt);

    exit;
}


$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| User not found
|--------------------------------------------------------------------------
*/

if (!$user) {

    http_response_code(401);

    echo json_encode([
        "message" => "Invalid email or password"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Password verification
|--------------------------------------------------------------------------
*/

$storedPassword = (string)$user["password"];

$passwordValid = false;


/*
|--------------------------------------------------------------------------
| If password is bcrypt hash
|--------------------------------------------------------------------------
*/

if (
    str_starts_with($storedPassword, '$2y$') ||
    str_starts_with($storedPassword, '$2a$') ||
    str_starts_with($storedPassword, '$2b$')
) {

    $passwordValid = password_verify(
        $password,
        $storedPassword
    );

}


/*
|--------------------------------------------------------------------------
| If password is old plain-text password
|--------------------------------------------------------------------------
*/

else {

    $passwordValid = hash_equals(
        $storedPassword,
        $password
    );

}


/*
|--------------------------------------------------------------------------
| Invalid password
|--------------------------------------------------------------------------
*/

if (!$passwordValid) {

    http_response_code(401);

    echo json_encode([
        "message" => "Invalid email or password"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Automatically upgrade plain-text password
|--------------------------------------------------------------------------
|
| If the existing database password is plain text,
| convert it to bcrypt after successful login.
|
*/

if (
    !str_starts_with($storedPassword, '$2y$') &&
    !str_starts_with($storedPassword, '$2a$') &&
    !str_starts_with($storedPassword, '$2b$')
) {

    $newHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    $updateSql = "
        UPDATE users
        SET password = ?
        WHERE id = ?
    ";


    $updateStmt = mysqli_prepare(
        $conn,
        $updateSql
    );


    if ($updateStmt) {

        $userId = (int)$user["id"];

        mysqli_stmt_bind_param(
            $updateStmt,
            "si",
            $newHash,
            $userId
        );

        mysqli_stmt_execute($updateStmt);

        mysqli_stmt_close($updateStmt);
    }
}


/*
|--------------------------------------------------------------------------
| Create JWT
|--------------------------------------------------------------------------
*/

$secretKey = "my_super_secret_key_123456789_ABC";

$issuedAt = time();

$expire = $issuedAt + 3600;


$payload = [

    "iat" => $issuedAt,

    "exp" => $expire,

    "userId" => (int)$user["id"],

    "username" => $user["username"],

    "email" => $user["email"],

    "role" => $user["role"]

];


$token = JWT::encode(
    $payload,
    $secretKey,
    "HS256"
);


/*
|--------------------------------------------------------------------------
| Success
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([

    "message" => "Login successful",

    "token" => $token,

    "userId" => (int)$user["id"],

    "username" => $user["username"],

    "email" => $user["email"],

    "role" => $user["role"]

], JSON_PRETTY_PRINT);

exit;

?>