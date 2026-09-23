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

    $headers = getallheaders();


    /* =========================
       CHECK AUTHORIZATION HEADER
    ========================= */

    if (!isset($headers["Authorization"])) {

        http_response_code(401);

        echo json_encode([
            "message" => "Authorization token is required"
        ]);

        exit;
    }


    $authHeader = $headers["Authorization"];


    /* =========================
       CHECK BEARER FORMAT
    ========================= */

    if (
        !preg_match(
            '/Bearer\s+(\S+)/',
            $authHeader,
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


    /* =========================
       JWT SECRET
    ========================= */

    $secretKey = "my_super_secret_key_123456789_ABC";


    /* =========================
       VERIFY JWT
    ========================= */

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

    if (!isset($user->role) || $user->role !== "admin") {

        http_response_code(403);

        echo json_encode([
            "message" => "Admin access required"
        ]);

        exit;
    }

    return $user;
}
?>