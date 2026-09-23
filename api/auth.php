<?php

require_once "../config/database.php";

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];


/* =========================
   POST - LOGIN
========================= */

if ($method === "POST") {

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (!$data) {

        http_response_code(400);

        echo json_encode([
            "message" => "Invalid JSON"
        ]);

        exit;
    }


    if (
        !isset($data["username"]) ||
        !isset($data["password"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "Username and password are required"
        ]);

        exit;
    }


    $username = mysqli_real_escape_string(
        $conn,
        $data["username"]
    );

    $password = mysqli_real_escape_string(
        $conn,
        $data["password"]
    );


    /* =========================
       FIND USER
    ========================= */

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE username = '$username'
         LIMIT 1"
    );


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    $user = mysqli_fetch_assoc($result);


    /* =========================
       CHECK USER
    ========================= */

    if (!$user) {

        http_response_code(401);

        echo json_encode([
            "message" => "Invalid username or password"
        ]);

        exit;
    }


    /* =========================
       CHECK PASSWORD
    ========================= */

    if ($user["password"] !== $password) {

        http_response_code(401);

        echo json_encode([
            "message" => "Invalid username or password"
        ]);

        exit;
    }


    /* =========================
       LOGIN SUCCESS
    ========================= */

    echo json_encode([
        "token" => "fake-jwt-token-" . $user["id"]
    ]);

    exit;
}


/* =========================
   METHOD NOT ALLOWED
========================= */

http_response_code(405);

echo json_encode([
    "message" => "Method not allowed"
]);

?>