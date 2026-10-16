<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once("../config/database.php");

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| GET - Get product(s)
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    // GET SINGLE PRODUCT
    if (isset($_GET["id"])) {

        $id = (int) $_GET["id"];

        $sql = "SELECT * FROM products WHERE id = $id";

        $result = mysqli_query($conn, $sql);

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
                "message" => "Product not found"
            ]);

            exit;
        }

        echo json_encode([
            "id" => (int)$row["id"],
            "title" => $row["title"],
            "price" => (float)$row["price"],
            "description" => $row["description"],
            "category" => $row["category"],
            "image" => $row["image"]
        ], JSON_PRETTY_PRINT);

        exit;
    }


    // GET ALL PRODUCTS

    $sql = "SELECT * FROM products";

    $result = mysqli_query($conn, $sql);

    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $products[] = [
            "id" => (int)$row["id"],
            "title" => $row["title"],
            "price" => (float)$row["price"],
            "description" => $row["description"],
            "category" => $row["category"],
            "image" => $row["image"]
        ];
    }

    echo json_encode($products, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| POST - Create product
|--------------------------------------------------------------------------
*/

if ($method === "POST") {

    // NO TOKEN REQUIRED

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
        !isset($data["title"]) ||
        !isset($data["price"]) ||
        !isset($data["description"]) ||
        !isset($data["category"]) ||
        !isset($data["image"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "All fields are required"
        ]);

        exit;
    }


    $title = mysqli_real_escape_string(
        $conn,
        $data["title"]
    );

    $price = (float)$data["price"];

    $description = mysqli_real_escape_string(
        $conn,
        $data["description"]
    );

    $category = mysqli_real_escape_string(
        $conn,
        $data["category"]
    );

    $image = mysqli_real_escape_string(
        $conn,
        $data["image"]
    );


    $sql = "
        INSERT INTO products
        (
            title,
            price,
            description,
            category,
            image
        )
        VALUES
        (
            '$title',
            '$price',
            '$description',
            '$category',
            '$image'
        )
    ";


    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to create product",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    $id = mysqli_insert_id($conn);

    http_response_code(201);

    echo json_encode([
        "id" => $id,
        "title" => $data["title"],
        "price" => $price,
        "description" => $data["description"],
        "category" => $data["category"],
        "image" => $data["image"]
    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| PUT - Update product
|--------------------------------------------------------------------------
*/

if ($method === "PUT") {

    require_once("../middleware/auth.php");

    requireAdmin();


    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }


    $id = (int)$_GET["id"];


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
        !isset($data["title"]) ||
        !isset($data["price"]) ||
        !isset($data["description"]) ||
        !isset($data["category"]) ||
        !isset($data["image"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "All fields are required"
        ]);

        exit;
    }


    $check = mysqli_query(
        $conn,
        "SELECT id FROM products WHERE id = $id"
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
            "message" => "Product not found"
        ]);

        exit;
    }


    $title = mysqli_real_escape_string(
        $conn,
        $data["title"]
    );

    $price = (float)$data["price"];

    $description = mysqli_real_escape_string(
        $conn,
        $data["description"]
    );

    $category = mysqli_real_escape_string(
        $conn,
        $data["category"]
    );

    $image = mysqli_real_escape_string(
        $conn,
        $data["image"]
    );


    $sql = "
        UPDATE products
        SET
            title = '$title',
            price = '$price',
            description = '$description',
            category = '$category',
            image = '$image'
        WHERE id = $id
    ";


    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update product",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    echo json_encode([
        "id" => $id,
        "title" => $data["title"],
        "price" => $price,
        "description" => $data["description"],
        "category" => $data["category"],
        "image" => $data["image"]
    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE - Delete product
|--------------------------------------------------------------------------
*/

if ($method === "DELETE") {

    require_once("../middleware/auth.php");

    requireAdmin();


    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }


    $id = (int)$_GET["id"];


    $check = mysqli_query(
        $conn,
        "SELECT id FROM products WHERE id = $id"
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
            "message" => "Product not found"
        ]);

        exit;
    }


    $result = mysqli_query(
        $conn,
        "DELETE FROM products WHERE id = $id"
    );


    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete product",
            "error" => mysqli_error($conn)
        ]);

        exit;
    }


    echo json_encode([
        "message" => "Product deleted successfully",
        "id" => $id
    ]);

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