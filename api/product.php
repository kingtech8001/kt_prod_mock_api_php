<?php

header("Content-Type: application/json");

require_once("../config/database.php");

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| GET - Get single product
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    if (!isset($_GET['id'])) {
        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }

    $id = intval($_GET['id']);

    $sql = "SELECT * FROM products WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        http_response_code(500);

        echo json_encode([
            "message" => "Database query failed"
        ]);

        exit;
    }

    if (mysqli_num_rows($result) == 0) {
        http_response_code(404);

        echo json_encode([
            "message" => "Product not found"
        ]);

        exit;
    }

    $product = mysqli_fetch_assoc($result);

    $product["id"] = (int)$product["id"];
    $product["price"] = (float)$product["price"];

    echo json_encode($product, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| PUT - Update complete product
|--------------------------------------------------------------------------
*/

if ($method === "PUT") {

    if (!isset($_GET['id'])) {
        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }

    $id = intval($_GET['id']);

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data["title"]) ||
        !isset($data["price"]) ||
        !isset($data["description"]) ||
        !isset($data["category"]) ||
        !isset($data["image"])
    ) {
        http_response_code(400);

        echo json_encode([
            "message" => "All fields are required for PUT"
        ]);

        exit;
    }

    $title = mysqli_real_escape_string($conn, $data["title"]);
    $price = (float)$data["price"];
    $description = mysqli_real_escape_string($conn, $data["description"]);
    $category = mysqli_real_escape_string($conn, $data["category"]);
    $image = mysqli_real_escape_string($conn, $data["image"]);

    $sql = "UPDATE products SET
            title = '$title',
            price = '$price',
            description = '$description',
            category = '$category',
            image = '$image'
            WHERE id = $id";

    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update product"
        ]);

        exit;
    }

    if (mysqli_affected_rows($conn) == 0) {

        $check = mysqli_query(
            $conn,
            "SELECT id FROM products WHERE id = $id"
        );

        if (mysqli_num_rows($check) == 0) {

            http_response_code(404);

            echo json_encode([
                "message" => "Product not found"
            ]);

            exit;
        }
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
| PATCH - Partially update product
|--------------------------------------------------------------------------
*/

if ($method === "PATCH") {

    if (!isset($_GET['id'])) {
        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }

    $id = intval($_GET['id']);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || count($data) == 0) {
        http_response_code(400);

        echo json_encode([
            "message" => "No data provided"
        ]);

        exit;
    }

    $allowedFields = [
        "title",
        "price",
        "description",
        "category",
        "image"
    ];

    $updates = [];

    foreach ($data as $field => $value) {

        if (in_array($field, $allowedFields)) {

            if ($field === "price") {

                $value = (float)$value;

            } else {

                $value = mysqli_real_escape_string(
                    $conn,
                    $value
                );
            }

            $updates[] = "$field = '$value'";
        }
    }

    if (count($updates) == 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "No valid fields provided"
        ]);

        exit;
    }

    $sql = "UPDATE products SET "
         . implode(", ", $updates)
         . " WHERE id = $id";

    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update product"
        ]);

        exit;
    }

    $result = mysqli_query(
        $conn,
        "SELECT * FROM products WHERE id = $id"
    );

    if (mysqli_num_rows($result) == 0) {

        http_response_code(404);

        echo json_encode([
            "message" => "Product not found"
        ]);

        exit;
    }

    $product = mysqli_fetch_assoc($result);

    $product["id"] = (int)$product["id"];
    $product["price"] = (float)$product["price"];

    echo json_encode($product, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE - Delete product
|--------------------------------------------------------------------------
*/

if ($method === "DELETE") {

    if (!isset($_GET['id'])) {
        http_response_code(400);

        echo json_encode([
            "message" => "Product ID is required"
        ]);

        exit;
    }

    $id = intval($_GET['id']);

    $sql = "DELETE FROM products WHERE id = $id";

    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete product"
        ]);

        exit;
    }

    if (mysqli_affected_rows($conn) == 0) {

        http_response_code(404);

        echo json_encode([
            "message" => "Product not found"
        ]);

        exit;
    }

    echo json_encode([
        "message" => "Product deleted successfully"
    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| Unsupported method
|--------------------------------------------------------------------------
*/

http_response_code(405);

echo json_encode([
    "message" => "Method not allowed"
]);