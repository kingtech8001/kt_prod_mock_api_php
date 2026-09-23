<?php

require_once "../config/database.php";
require_once "../middleware/auth.php";

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];


/* =========================================================
   POST - CREATE USER / REGISTER
   Public route - JWT required નથી
========================================================= */

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
        !isset($data["email"]) ||
        !isset($data["username"]) ||
        !isset($data["password"]) ||
        !isset($data["name"]["firstname"]) ||
        !isset($data["name"]["lastname"]) ||
        !isset($data["phone"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "All fields are required"
        ]);

        exit;
    }

    $email = mysqli_real_escape_string(
        $conn,
        $data["email"]
    );

    $username = mysqli_real_escape_string(
        $conn,
        $data["username"]
    );

    $password = mysqli_real_escape_string(
        $conn,
        $data["password"]
    );

    $firstname = mysqli_real_escape_string(
        $conn,
        $data["name"]["firstname"]
    );

    $lastname = mysqli_real_escape_string(
        $conn,
        $data["name"]["lastname"]
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $data["phone"]
    );


    /* Check existing email */

    $checkEmail = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE email = '$email' LIMIT 1"
    );

    if (!$checkEmail) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    if (mysqli_num_rows($checkEmail) > 0) {

        http_response_code(409);

        echo json_encode([
            "message" => "Email already exists"
        ]);

        exit;
    }


    /* Check existing username */

    $checkUsername = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE username = '$username' LIMIT 1"
    );

    if (!$checkUsername) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    if (mysqli_num_rows($checkUsername) > 0) {

        http_response_code(409);

        echo json_encode([
            "message" => "Username already exists"
        ]);

        exit;
    }


    /* Insert user */

    $sql = "
        INSERT INTO users
        (
            email,
            username,
            password,
            firstname,
            lastname,
            phone
        )
        VALUES
        (
            '$email',
            '$username',
            '$password',
            '$firstname',
            '$lastname',
            '$phone'
        )
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to create user"
        ]);

        exit;
    }


    $userId = mysqli_insert_id($conn);


    http_response_code(201);

    echo json_encode([
        "message" => "User created successfully",
        "id" => $userId,
        "email" => $email,
        "username" => $username,
        "name" => [
            "firstname" => $firstname,
            "lastname" => $lastname
        ],
        "phone" => $phone
    ]);

    exit;
}


/* =========================================================
   JWT AUTHENTICATION
   POST સિવાયના બધા operations protected
========================================================= */

$user = authenticate();

$loggedInUserId = (int) $user->userId;


/* =========================================================
   GET USER BY ID
========================================================= */

if ($method === "GET" && isset($_GET["id"])) {

    $id = (int) $_GET["id"];


    /* User can only access own account */

    if ($id !== $loggedInUserId) {

        http_response_code(403);

        echo json_encode([
            "message" => "You can only access your own account"
        ]);

        exit;
    }


    $result = mysqli_query(
        $conn,
        "SELECT id, email, username, firstname, lastname, phone
         FROM users
         WHERE id = $id
         LIMIT 1"
    );


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    $row = mysqli_fetch_assoc($result);


    if (!$row) {

        http_response_code(404);

        echo json_encode([
            "message" => "User not found"
        ]);

        exit;
    }


    echo json_encode([
        "id" => (int) $row["id"],
        "email" => $row["email"],
        "username" => $row["username"],
        "name" => [
            "firstname" => $row["firstname"],
            "lastname" => $row["lastname"]
        ],
        "phone" => $row["phone"]
    ]);

    exit;
}


/* =========================================================
   GET ALL USERS
========================================================= */

if ($method === "GET") {

    $result = mysqli_query(
        $conn,
        "SELECT id, email, username, firstname, lastname, phone
         FROM users"
    );


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    $users = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $users[] = [
            "id" => (int) $row["id"],
            "email" => $row["email"],
            "username" => $row["username"],
            "name" => [
                "firstname" => $row["firstname"],
                "lastname" => $row["lastname"]
            ],
            "phone" => $row["phone"]
        ];
    }


    echo json_encode($users);

    exit;
}


/* =========================================================
   PUT - UPDATE USER
========================================================= */

if ($method === "PUT") {

    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "User ID is required"
        ]);

        exit;
    }


    $id = (int) $_GET["id"];


    /* User can only update own account */

    if ($id !== $loggedInUserId) {

        http_response_code(403);

        echo json_encode([
            "message" => "You can only update your own account"
        ]);

        exit;
    }


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
        !isset($data["email"]) ||
        !isset($data["username"]) ||
        !isset($data["password"]) ||
        !isset($data["name"]["firstname"]) ||
        !isset($data["name"]["lastname"]) ||
        !isset($data["phone"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "All fields are required"
        ]);

        exit;
    }


    /* Check user */

    $check = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE id = $id LIMIT 1"
    );


    if (!$check) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    if (mysqli_num_rows($check) === 0) {

        http_response_code(404);

        echo json_encode([
            "message" => "User not found"
        ]);

        exit;
    }


    $email = mysqli_real_escape_string(
        $conn,
        $data["email"]
    );

    $username = mysqli_real_escape_string(
        $conn,
        $data["username"]
    );

    $password = mysqli_real_escape_string(
        $conn,
        $data["password"]
    );

    $firstname = mysqli_real_escape_string(
        $conn,
        $data["name"]["firstname"]
    );

    $lastname = mysqli_real_escape_string(
        $conn,
        $data["name"]["lastname"]
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $data["phone"]
    );


    /* Update user */

    $sql = "
        UPDATE users
        SET
            email = '$email',
            username = '$username',
            password = '$password',
            firstname = '$firstname',
            lastname = '$lastname',
            phone = '$phone'
        WHERE id = $id
    ";


    $result = mysqli_query($conn, $sql);


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update user"
        ]);

        exit;
    }


    echo json_encode([
        "message" => "User updated successfully",
        "id" => $id,
        "email" => $email,
        "username" => $username,
        "name" => [
            "firstname" => $firstname,
            "lastname" => $lastname
        ],
        "phone" => $phone
    ]);

    exit;
}


/* =========================================================
   DELETE - DELETE USER
========================================================= */

if ($method === "DELETE") {

    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "User ID is required"
        ]);

        exit;
    }


    $id = (int) $_GET["id"];


    /* User can only delete own account */

    if ($id !== $loggedInUserId) {

        http_response_code(403);

        echo json_encode([
            "message" => "You can only delete your own account"
        ]);

        exit;
    }


    /* Check user exists */

    $check = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE id = $id LIMIT 1"
    );


    if (!$check) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    if (mysqli_num_rows($check) === 0) {

        http_response_code(404);

        echo json_encode([
            "message" => "User not found"
        ]);

        exit;
    }


    /* Delete user */

    $result = mysqli_query(
        $conn,
        "DELETE FROM users WHERE id = $id"
    );


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete user"
        ]);

        exit;
    }


    echo json_encode([
        "message" => "User deleted successfully",
        "id" => $id
    ]);

    exit;
}


/* =========================================================
   METHOD NOT ALLOWED
========================================================= */

http_response_code(405);

echo json_encode([
    "message" => "Method not allowed"
]);

?>