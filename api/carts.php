<?php

require_once "../config/database.php";

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| GET - Get cart(s)
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    // GET single cart
    if (isset($_GET["id"])) {

        $id = (int) $_GET["id"];

        $result = mysqli_query(
            $conn,
            "SELECT * FROM carts WHERE id = $id"
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
                "message" => "Cart not found"
            ]);

            exit;
        }

        $cartId = (int) $row["id"];

        $productQuery = mysqli_query(
            $conn,
            "SELECT productId, quantity
             FROM cart_products
             WHERE cartId = $cartId"
        );

        if (!$productQuery) {

            http_response_code(500);

            echo json_encode([
                "message" => "Database error"
            ]);

            exit;
        }

        $products = [];

        while ($product = mysqli_fetch_assoc($productQuery)) {

            $products[] = [
                "productId" => (int) $product["productId"],
                "quantity" => (int) $product["quantity"]
            ];
        }

        echo json_encode([
            "id" => (int) $row["id"],
            "userId" => (int) $row["userId"],
            "date" => $row["date"],
            "products" => $products
        ], JSON_PRETTY_PRINT);

        exit;
    }


    // GET all carts

    $result = mysqli_query(
        $conn,
        "SELECT * FROM carts"
    );

    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    $carts = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $cartId = (int) $row["id"];

        $productQuery = mysqli_query(
            $conn,
            "SELECT productId, quantity
             FROM cart_products
             WHERE cartId = $cartId"
        );

        $products = [];

        while ($product = mysqli_fetch_assoc($productQuery)) {

            $products[] = [
                "productId" => (int) $product["productId"],
                "quantity" => (int) $product["quantity"]
            ];
        }

        $carts[] = [
            "id" => $cartId,
            "userId" => (int) $row["userId"],
            "date" => $row["date"],
            "products" => $products
        ];
    }

    echo json_encode(
        $carts,
        JSON_PRETTY_PRINT
    );

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

?>