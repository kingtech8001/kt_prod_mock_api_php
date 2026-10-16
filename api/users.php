<?php

require_once "../config/database.php";
require_once "../middleware/auth.php";

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| POST - Create User
|--------------------------------------------------------------------------
*/

if ($method === "POST") {

    /*
    |--------------------------------------------------------------------------
    | READ JSON BODY
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

    $email = $data["email"] ?? "";
    $username = $data["username"] ?? "";
    $password = $data["password"] ?? "";

    $firstname = $data["firstname"] ?? "";
    $lastname = $data["lastname"] ?? "";

    $phone = $data["phone"] ?? "";

    $role = $data["role"] ?? "user";

    $city = $data["city"] ?? null;
    $street = $data["street"] ?? null;

    $number = $data["number"] ?? 0;

    $zipcode = $data["zipcode"] ?? null;


    /*
    |--------------------------------------------------------------------------
    | REQUIRED FIELDS
    |--------------------------------------------------------------------------
    */

    if (
        empty($email) ||
        empty($username) ||
        empty($password)
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "Email, username and password are required"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EMAIL / USERNAME
    |--------------------------------------------------------------------------
    */

    $emailCheck = mysqli_real_escape_string(
        $conn,
        $email
    );

    $usernameCheck = mysqli_real_escape_string(
        $conn,
        $username
    );

    $check = mysqli_query(
        $conn,
        "SELECT id FROM users
         WHERE email = '$emailCheck'
         OR username = '$usernameCheck'"
    );


    if (!$check) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    if (mysqli_num_rows($check) > 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "Email or username already exists"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE VALUES
    |--------------------------------------------------------------------------
    */

    $email = mysqli_real_escape_string(
        $conn,
        $email
    );

    $username = mysqli_real_escape_string(
        $conn,
        $username
    );

    $password = mysqli_real_escape_string(
        $conn,
        $password
    );

    $firstname = mysqli_real_escape_string(
        $conn,
        $firstname
    );

    $lastname = mysqli_real_escape_string(
        $conn,
        $lastname
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $phone
    );

    $role = mysqli_real_escape_string(
        $conn,
        $role
    );

    $city = mysqli_real_escape_string(
        $conn,
        $city ?? ""
    );

    $street = mysqli_real_escape_string(
        $conn,
        $street ?? ""
    );

    $zipcode = mysqli_real_escape_string(
        $conn,
        $zipcode ?? ""
    );

    $number = (int)$number;


    /*
    |--------------------------------------------------------------------------
    | INSERT USER
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO users
        (
            email,
            username,
            password,
            firstname,
            lastname,
            phone,
            role,
            city,
            street,
            number,
            zipcode
        )
        VALUES
        (
            '$email',
            '$username',
            '$password',
            '$firstname',
            '$lastname',
            '$phone',
            '$role',
            '$city',
            '$street',
            $number,
            '$zipcode'
        )
    ";


    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to create user",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | GET CREATED USER ID
    |--------------------------------------------------------------------------
    */

    $id = mysqli_insert_id($conn);


    /*
    |--------------------------------------------------------------------------
    | RETURN USER
    |--------------------------------------------------------------------------
    */

    http_response_code(201);

    echo json_encode([

        "address" => [
            "geolocation" => [
                "lat" => null,
                "long" => null
            ],
            "city" => $city ?: null,
            "street" => $street ?: null,
            "number" => $number,
            "zipcode" => $zipcode ?: null
        ],

        "id" => (int)$id,

        "email" => $email,

        "username" => $username,

        "password" => $password,

        "name" => [
            "firstname" => $firstname,
            "lastname" => $lastname
        ],

        "phone" => $phone,

        "__v" => 0

    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| GET - Get User(s)
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE USER
    |--------------------------------------------------------------------------
    */

    if (isset($_GET['id'])) {

        $id = (int) $_GET['id'];

        $query = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE id = $id"
        );

        if (!$query) {

            http_response_code(500);

            echo json_encode([
                "message" => "Database error"
            ]);

            exit;
        }

        if (mysqli_num_rows($query) === 0) {

            http_response_code(404);

            echo json_encode([
                "message" => "User not found"
            ]);

            exit;
        }

        $row = mysqli_fetch_assoc($query);

        echo json_encode([

            "address" => [
                "geolocation" => [
                    "lat" => null,
                    "long" => null
                ],
                "city" => $row['city'] ?? null,
                "street" => $row['street'] ?? null,
                "number" => isset($row['number'])
                    ? (int)$row['number']
                    : 0,
                "zipcode" => $row['zipcode'] ?? null
            ],

            "id" => (int)$row['id'],

            "email" => $row['email'],

            "username" => $row['username'],

            "password" => $row['password'],

            "name" => [
                "firstname" => $row['firstname'] ?? "",
                "lastname" => $row['lastname'] ?? ""
            ],

            "phone" => $row['phone'] ?? null,

            "__v" => 0
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users"
    );

    if (!$query) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    $users = [];

    while ($row = mysqli_fetch_assoc($query)) {

        $users[] = [

            "address" => [
                "geolocation" => [
                    "lat" => null,
                    "long" => null
                ],
                "city" => $row['city'] ?? null,
                "street" => $row['street'] ?? null,
                "number" => isset($row['number'])
                    ? (int)$row['number']
                    : 0,
                "zipcode" => $row['zipcode'] ?? null
            ],

            "id" => (int)$row['id'],

            "email" => $row['email'],

            "username" => $row['username'],

            "password" => $row['password'],

            "name" => [
                "firstname" => $row['firstname'] ?? "",
                "lastname" => $row['lastname'] ?? ""
            ],

            "phone" => $row['phone'] ?? null,

            "__v" => 0
        ];
    }

    echo json_encode($users);

    exit;
}


/*
|--------------------------------------------------------------------------
| PUT - Update Own Profile
|--------------------------------------------------------------------------
*/

if ($method === "PUT") {

    /*
    |--------------------------------------------------------------------------
    | CHECK USER ID
    |--------------------------------------------------------------------------
    */

    if (!isset($_GET['id'])) {

        http_response_code(400);

        echo json_encode([
            "message" => "User ID is required"
        ]);

        exit;
    }

    $id = (int) $_GET['id'];


    /*
    |--------------------------------------------------------------------------
    | CHECK TOKEN + USER OWNERSHIP
    |--------------------------------------------------------------------------
    */

    requireUserId($id);


    /*
    |--------------------------------------------------------------------------
    | READ JSON BODY
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT USER
    |--------------------------------------------------------------------------
    */

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id = $id"
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


    $currentUser = mysqli_fetch_assoc($check);


    /*
    |--------------------------------------------------------------------------
    | KEEP OLD VALUES IF NOT SENT
    |--------------------------------------------------------------------------
    */

    $username = $data["username"]
        ?? $currentUser["username"];

    $email = $data["email"]
        ?? $currentUser["email"];

    $firstname = $data["firstname"]
        ?? $currentUser["firstname"];

    $lastname = $data["lastname"]
        ?? $currentUser["lastname"];

    $phone = $data["phone"]
        ?? $currentUser["phone"];

    $city = $data["city"]
        ?? ($currentUser["city"] ?? null);

    $street = $data["street"]
        ?? ($currentUser["street"] ?? null);

    $number = $data["number"]
        ?? ($currentUser["number"] ?? 0);

    $zipcode = $data["zipcode"]
        ?? ($currentUser["zipcode"] ?? null);


    /*
    |--------------------------------------------------------------------------
    | ESCAPE VALUES
    |--------------------------------------------------------------------------
    */

    $username = mysqli_real_escape_string(
        $conn,
        $username
    );

    $email = mysqli_real_escape_string(
        $conn,
        $email
    );

    $firstname = mysqli_real_escape_string(
        $conn,
        $firstname
    );

    $lastname = mysqli_real_escape_string(
        $conn,
        $lastname
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $phone
    );

    $city = mysqli_real_escape_string(
        $conn,
        $city ?? ""
    );

    $street = mysqli_real_escape_string(
        $conn,
        $street ?? ""
    );

    $zipcode = mysqli_real_escape_string(
        $conn,
        $zipcode ?? ""
    );

    $number = (int)$number;


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE users
        SET
            username = '$username',
            email = '$email',
            firstname = '$firstname',
            lastname = '$lastname',
            phone = '$phone',
            city = '$city',
            street = '$street',
            number = $number,
            zipcode = '$zipcode'
        WHERE id = $id
    ";


    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update profile",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | RETURN UPDATED USER
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "message" => "Profile updated successfully",

        "user" => [

            "address" => [
                "geolocation" => [
                    "lat" => null,
                    "long" => null
                ],
                "city" => $city,
                "street" => $street,
                "number" => $number,
                "zipcode" => $zipcode
            ],

            "id" => $id,

            "email" => $email,

            "username" => $username,

            "password" => $currentUser["password"],

            "name" => [
                "firstname" => $firstname,
                "lastname" => $lastname
            ],

            "phone" => $phone,

            "__v" => 0
        ]

    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| Unsupported Method
|--------------------------------------------------------------------------
*/

http_response_code(405);

echo json_encode([
    "message" => "Method not allowed"
]);

?>