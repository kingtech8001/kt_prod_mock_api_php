<?php

header("Content-Type: application/json");

require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/middleware/auth.php";

$method = $_SERVER["REQUEST_METHOD"];


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
| POST /checkout.php
|
| User's JWT
|      ↓
| Find user's cart
|      ↓
| Get cart products
|      ↓
| Create orders
|      ↓
| Clear cart
|--------------------------------------------------------------------------
*/

if ($method === "POST") {

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATE USER
    |--------------------------------------------------------------------------
    */

    $user = authenticate();

    $userId = (int) $user->userId;


    /*
    |--------------------------------------------------------------------------
    | FIND USER CART
    |--------------------------------------------------------------------------
    */

    $cartResult = mysqli_query(
        $conn,
        "SELECT id
         FROM carts
         WHERE userId = $userId
         LIMIT 1"
    );

    if (!$cartResult) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }


    $cart = mysqli_fetch_assoc($cartResult);


    if (!$cart) {

        http_response_code(404);

        echo json_encode([
            "message" => "Cart not found"
        ]);

        exit;
    }


    $cartId = (int) $cart["id"];


    /*
    |--------------------------------------------------------------------------
    | GET CART PRODUCTS
    |--------------------------------------------------------------------------
    */

    $itemsResult = mysqli_query(
        $conn,
        "SELECT
            cp.productId,
            cp.quantity,
            p.title,
            p.price
         FROM cart_products cp
         INNER JOIN products p
            ON cp.productId = p.id
         WHERE cp.cartId = $cartId"
    );


    if (!$itemsResult) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to get cart products"
        ]);

        exit;
    }


    if (mysqli_num_rows($itemsResult) === 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "Cart is empty"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | START TRANSACTION
    |--------------------------------------------------------------------------
    */

    mysqli_begin_transaction($conn);

    try {

        $orders = [];
        $grandTotal = 0;


        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER FOR EACH CART ITEM
        |--------------------------------------------------------------------------
        */

        while ($item = mysqli_fetch_assoc($itemsResult)) {

            $productId = (int) $item["productId"];
            $quantity = (int) $item["quantity"];
            $price = (float) $item["price"];

            $totalAmount = $price * $quantity;

            $grandTotal += $totalAmount;


            /*
            |--------------------------------------------------------------
            | INSERT ORDER
            |--------------------------------------------------------------
            */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO orders
                (
                    user_id,
                    product_id,
                    quantity,
                    price,
                    total_amount,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );


            if (!$stmt) {
                throw new Exception("Failed to prepare order query");
            }


            $status = "Pending";


            mysqli_stmt_bind_param(
                $stmt,
                "iiidds",
                $userId,
                $productId,
                $quantity,
                $price,
                $totalAmount,
                $status
            );


            if (!mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                throw new Exception("Failed to create order");
            }


            $orderId = mysqli_insert_id($conn);

            mysqli_stmt_close($stmt);


            /*
            |--------------------------------------------------------------
            | ADD ORDER TO RESPONSE
            |--------------------------------------------------------------
            */

            $orders[] = [
                "id" => (int) $orderId,
                "productId" => $productId,
                "title" => $item["title"],
                "quantity" => $quantity,
                "price" => $price,
                "totalAmount" => $totalAmount,
                "status" => $status
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR CART
        |--------------------------------------------------------------------------
        */

        $deleteItems = mysqli_query(
            $conn,
            "DELETE FROM cart_products
             WHERE cartId = $cartId"
        );


        if (!$deleteItems) {

            throw new Exception("Failed to clear cart");
        }


        /*
        |--------------------------------------------------------------------------
        | COMMIT TRANSACTION
        |--------------------------------------------------------------------------
        */

        mysqli_commit($conn);


        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        http_response_code(201);

        echo json_encode([
            "message" => "Checkout successful",
            "userId" => $userId,
            "orders" => $orders,
            "grandTotal" => $grandTotal
        ], JSON_PRETTY_PRINT);

        exit;


    } catch (Exception $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK IF ANYTHING FAILS
        |--------------------------------------------------------------------------
        */

        mysqli_rollback($conn);

        http_response_code(500);

        echo json_encode([
            "message" => "Checkout failed"
        ]);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| METHOD NOT ALLOWED
|--------------------------------------------------------------------------
*/

http_response_code(405);

echo json_encode([
    "message" => "Method not allowed"
]);

?>