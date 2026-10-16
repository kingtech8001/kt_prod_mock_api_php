<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


/* =========================
   AUTHENTICATE USER
========================= */

function authenticate()
{
    header("Content-Type: application/json");

    /*
    |--------------------------------------------------------------------------
    | GET AUTHORIZATION HEADER
    |--------------------------------------------------------------------------
    */

    $headers = getallheaders();

    $authHeader = null;

    // Case-insensitive header search
    foreach ($headers as $key => $value) {

        if (strtolower($key) === "authorization") {
            $authHeader = $value;
            break;
        }
    }

    // Fallback for some Apache/Hostinger configurations
    if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK AUTHORIZATION HEADER
    |--------------------------------------------------------------------------
    */

    if (!$authHeader) {

        http_response_code(401);

        echo json_encode([
            "message" => "Authorization token is required"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK BEARER FORMAT
    |--------------------------------------------------------------------------
    */

    if (
        !preg_match(
            '/^Bearer\s+(\S+)$/i',
            trim($authHeader),
            $matches
        )
    ) {

        http_response_code(401);

        echo json_encode([
            "message" => "Invalid authorization format"
        ]);

        exit;
    }


    $token = $matches[1];


    /*
    |--------------------------------------------------------------------------
    | JWT SECRET
    |--------------------------------------------------------------------------
    */

    $secretKey = "my_super_secret_key_123456789_ABC";


    /*
    |--------------------------------------------------------------------------
    | VERIFY JWT
    |--------------------------------------------------------------------------
    */

    try {

        $decoded = JWT::decode(
            $token,
            new Key($secretKey, "HS256")
        );

        return $decoded;

    } catch (Exception $e) {

        http_response_code(401);

        echo json_encode([
            "message" => "Invalid or expired token"
        ]);

        exit;
    }
}


/* =========================
   CHECK USER OWNERSHIP
========================= */

function requireUserId($requestedUserId)
{
    $user = authenticate();

    $loggedInUserId = (int) $user->userId;

    $requestedUserId = (int) $requestedUserId;


    if ($loggedInUserId !== $requestedUserId) {

        http_response_code(403);

        echo json_encode([
            "message" => "Access denied"
        ]);

        exit;
    }


    return $user;
}


/* =========================
   CHECK ADMIN ROLE
========================= */

function requireAdmin()
{
    $user = authenticate();


    if (
        !isset($user->role) ||
        $user->role !== "admin"
    ) {

        http_response_code(403);

        echo json_encode([
            "message" => "Admin access required"
        ]);

        exit;
    }


    return $user;
}

?>