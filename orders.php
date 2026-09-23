<?php

header("Content-Type: application/json");

require_once "../config/database.php";
require_once "../middleware/auth.php";

$method = $_SERVER["REQUEST_METHOD"];


/*
|--------------------------------------------------------------------------
| GET ORDERS
|--------------------------------------------------------------------------
| Admin  -> all orders
| User   -> own orders
| GET ?id=1 -> single order
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    $user = authenticate();

    $userId = (int)$user->userId;
    $isAdmin = isset($user->role) && $user->role === "admin";


    /*
    |----------------------------------------------------------------------
    | GET SINGLE ORDER
    |----------------------------------------------------------------------
    */

    if (isset($_GET["id"])) {

        $orderId = (int)$_GET["id"];

        $stmt = $conn->prepare(
            "SELECT 
                id,
                user_id,
                product_id,
                quantity,
                price,
                total_amount,
                status,
                created_at
             FROM orders
             WHERE id = ?"
        );

        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        $stmt->close();


        if (!$order) {

            http_response_code(404);

            echo json_encode([
                "message" => "Order not found"
            ]);

            exit;
        }


        /*
        |------------------------------------------------------------------
        | USER CAN ONLY SEE OWN ORDER
        |------------------------------------------------------------------
        */

        if (!$isAdmin && (int)$order["user_id"] !== $userId) {

            http_response_code(403);

            echo json_encode([
                "message" => "Access denied"
            ]);

            exit;
        }


        echo json_encode([
            "id" => (int)$order["id"],
            "userId" => (int)$order["user_id"],
            "productId" => (int)$order["product_id"],
            "quantity" => (int)$order["quantity"],
            "price" => (float)$order["price"],
            "totalAmount" => (float)$order["total_amount"],
            "status" => $order["status"],
            "createdAt" => $order["created_at"]
        ], JSON_PRETTY_PRINT);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | GET ALL ORDERS
    |----------------------------------------------------------------------
    */

    if ($isAdmin) {

        $stmt = $conn->prepare(
            "SELECT
                id,
                user_id,
                product_id,
                quantity,
                price,
                total_amount,
                status,
                created_at
             FROM orders
             ORDER BY id DESC"
        );

    } else {

        $stmt = $conn->prepare(
            "SELECT
                id,
                user_id,
                product_id,
                quantity,
                price,
                total_amount,
                status,
                created_at
             FROM orders
             WHERE user_id = ?
             ORDER BY id DESC"
        );

        $stmt->bind_param("i", $userId);
    }


    $stmt->execute();

    $result = $stmt->get_result();

    $orders = [];


    while ($row = $result->fetch_assoc()) {

        $orders[] = [
            "id" => (int)$row["id"],
            "userId" => (int)$row["user_id"],
            "productId" => (int)$row["product_id"],
            "quantity" => (int)$row["quantity"],
            "price" => (float)$row["price"],
            "totalAmount" => (float)$row["total_amount"],
            "status" => $row["status"],
            "createdAt" => $row["created_at"]
        ];
    }


    $stmt->close();

    echo json_encode($orders, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| POST - CREATE ORDER
|--------------------------------------------------------------------------
*/

if ($method === "POST") {

    $user = authenticate();

    $userId = (int)$user->userId;


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
    |----------------------------------------------------------------------
    | VALIDATE INPUT
    |----------------------------------------------------------------------
    */

    if (
        !isset($data["productId"]) ||
        !isset($data["quantity"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "Product ID and quantity are required"
        ]);

        exit;
    }


    $productId = (int)$data["productId"];
    $quantity = (int)$data["quantity"];


    if ($productId <= 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "Invalid product ID"
        ]);

        exit;
    }


    if ($quantity <= 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "Quantity must be greater than 0"
        ]);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | GET PRODUCT PRICE FROM DATABASE
    |----------------------------------------------------------------------
    |
    | Do NOT trust price sent by client.
    |
    */

    $stmt = $conn->prepare(
        "SELECT id, price
         FROM products
         WHERE id = ?"
    );

    $stmt->bind_param("i", $productId);
    $stmt->execute();

    $result = $stmt->get_result();

    $product = $result->fetch_assoc();

    $stmt->close();


    if (!$product) {

        http_response_code(404);

        echo json_encode([
            "message" => "Product not found"
        ]);

        exit;
    }


    $price = (float)$product["price"];

    $totalAmount = $price * $quantity;


    /*
    |----------------------------------------------------------------------
    | CREATE ORDER
    |----------------------------------------------------------------------
    */

    $status = "Pending";


    $stmt = $conn->prepare(
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


    $stmt->bind_param(
        "iiidds",
        $userId,
        $productId,
        $quantity,
        $price,
        $totalAmount,
        $status
    );


    if (!$stmt->execute()) {

        $stmt->close();

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to create order"
        ]);

        exit;
    }


    $orderId = $stmt->insert_id;

    $stmt->close();


    http_response_code(201);

    echo json_encode([
        "id" => $orderId,
        "userId" => $userId,
        "productId" => $productId,
        "quantity" => $quantity,
        "price" => $price,
        "totalAmount" => $totalAmount,
        "status" => $status
    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| PUT - UPDATE ORDER STATUS
|--------------------------------------------------------------------------
| Only admin can change order status.
|--------------------------------------------------------------------------
*/

if ($method === "PUT") {

    $user = requireAdmin();


    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Order ID is required"
        ]);

        exit;
    }


    $orderId = (int)$_GET["id"];


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


    if (!isset($data["status"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Status is required"
        ]);

        exit;
    }


    $status = trim($data["status"]);


    /*
    |----------------------------------------------------------------------
    | ALLOWED ORDER STATUS
    |----------------------------------------------------------------------
    */

    $allowedStatuses = [
        "Pending",
        "Processing",
        "Shipped",
        "Delivered",
        "Cancelled"
    ];


    if (!in_array($status, $allowedStatuses, true)) {

        http_response_code(400);

        echo json_encode([
            "message" => "Invalid order status",
            "allowedStatuses" => $allowedStatuses
        ]);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | CHECK ORDER EXISTS
    |----------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "SELECT id
         FROM orders
         WHERE id = ?"
    );

    $stmt->bind_param("i", $orderId);
    $stmt->execute();

    $result = $stmt->get_result();

    $order = $result->fetch_assoc();

    $stmt->close();


    if (!$order) {

        http_response_code(404);

        echo json_encode([
            "message" => "Order not found"
        ]);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | UPDATE STATUS
    |----------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "UPDATE orders
         SET status = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "si",
        $status,
        $orderId
    );


    if (!$stmt->execute()) {

        $stmt->close();

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update order"
        ]);

        exit;
    }


    $stmt->close();


    echo json_encode([
        "message" => "Order status updated successfully",
        "id" => $orderId,
        "status" => $status
    ], JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE - DELETE ORDER
|--------------------------------------------------------------------------
| Admin can delete any order.
| User can delete only their own order.
|--------------------------------------------------------------------------
*/

if ($method === "DELETE") {

    $user = authenticate();

    $userId = (int)$user->userId;
    $isAdmin = isset($user->role) && $user->role === "admin";


    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Order ID is required"
        ]);

        exit;
    }


    $orderId = (int)$_GET["id"];


    /*
    |----------------------------------------------------------------------
    | FIND ORDER
    |----------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "SELECT id, user_id
         FROM orders
         WHERE id = ?"
    );

    $stmt->bind_param("i", $orderId);
    $stmt->execute();

    $result = $stmt->get_result();

    $order = $result->fetch_assoc();

    $stmt->close();


    if (!$order) {

        http_response_code(404);

        echo json_encode([
            "message" => "Order not found"
        ]);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | OWNERSHIP CHECK
    |----------------------------------------------------------------------
    */

    if (
        !$isAdmin &&
        (int)$order["user_id"] !== $userId
    ) {

        http_response_code(403);

        echo json_encode([
            "message" => "Access denied"
        ]);

        exit;
    }


    /*
    |----------------------------------------------------------------------
    | DELETE
    |----------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "DELETE FROM orders
         WHERE id = ?"
    );

    $stmt->bind_param("i", $orderId);


    if (!$stmt->execute()) {

        $stmt->close();

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete order"
        ]);

        exit;
    }


    $stmt->close();


    echo json_encode([
        "message" => "Order deleted successfully",
        "id" => $orderId
    ]);

    exit;
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