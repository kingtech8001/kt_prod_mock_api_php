<?php

require_once "../config/database.php";
require_once "../middleware/auth.php";

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];

/*
|--------------------------------------------------------------------------
| JWT Authentication
|--------------------------------------------------------------------------
*/

$user = authenticate();

$loggedInUserId = (int) $user->userId;


/*
|--------------------------------------------------------------------------
| GET - Get cart(s)
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE CART
    |--------------------------------------------------------------------------
    */

    if (isset($_GET["id"])) {

        $id = (int) $_GET["id"];

        $sql = "
            SELECT
                c.id,
                c.userId,
                c.date,
                cp.productId,
                cp.quantity
            FROM carts c
            LEFT JOIN cart_products cp
                ON c.id = cp.cartId
            WHERE c.id = $id
            AND c.userId = $loggedInUserId
        ";

        $result = mysqli_query($conn, $sql);

        if (!$result) {

            http_response_code(500);

            echo json_encode([
                "message" => "Database error"
            ]);

            exit;
        }

        $cart = null;

        while ($row = mysqli_fetch_assoc($result)) {

            if ($cart === null) {

                $cart = [
                    "id" => (int) $row["id"],
                    "userId" => (int) $row["userId"],
                    "date" => $row["date"],
                    "products" => []
                ];
            }

            if ($row["productId"] !== null) {

                $cart["products"][] = [
                    "productId" => (int) $row["productId"],
                    "quantity" => (int) $row["quantity"]
                ];
            }
        }

        if ($cart === null) {

            http_response_code(404);

            echo json_encode([
                "message" => "Cart not found"
            ]);

            exit;
        }

        echo json_encode(
            $cart,
            JSON_PRETTY_PRINT
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL CARTS
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            c.id,
            c.userId,
            c.date,
            cp.productId,
            cp.quantity
        FROM carts c
        LEFT JOIN cart_products cp
            ON c.id = cp.cartId
        WHERE c.userId = $loggedInUserId
        ORDER BY c.id
    ";

    $result = mysqli_query($conn, $sql);

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

        if (!isset($carts[$cartId])) {

            $carts[$cartId] = [
                "id" => $cartId,
                "userId" => (int) $row["userId"],
                "date" => $row["date"],
                "products" => []
            ];
        }

        if ($row["productId"] !== null) {

            $carts[$cartId]["products"][] = [
                "productId" => (int) $row["productId"],
                "quantity" => (int) $row["quantity"]
            ];
        }
    }

    echo json_encode(
        array_values($carts),
        JSON_PRETTY_PRINT
    );

    exit;
}
/*
|--------------------------------------------------------------------------
| POST - Create Cart
|--------------------------------------------------------------------------
*/

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
        !isset($data["date"]) ||
        !isset($data["products"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "userId, date and products are required"
        ]);

        exit;
    }


    if (!is_array($data["products"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Products must be an array"
        ]);

        exit;
    }


    $userId = $loggedInUserId;

    $date = mysqli_real_escape_string(
        $conn,
        $data["date"]
    );


    /*
    |--------------------------------------------------------------------------
    | Check user exists
    |--------------------------------------------------------------------------
    */

    $checkUser = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE id = $userId"
    );

    if (!$checkUser) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    if (mysqli_num_rows($checkUser) === 0) {

        http_response_code(404);

        echo json_encode([
            "message" => "User not found"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Create cart
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO carts
        (
            userId,
            date
        )
        VALUES
        (
            $userId,
            '$date'
        )
    ";


    if (!mysqli_query($conn, $sql)) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to create cart"
        ]);

        exit;
    }


    $cartId = mysqli_insert_id($conn);


    /*
    |--------------------------------------------------------------------------
    | Add products
    |--------------------------------------------------------------------------
    */

    foreach ($data["products"] as $product) {

        if (
            !isset($product["productId"]) ||
            !isset($product["quantity"])
        ) {

            // Remove created cart if product data is invalid
            mysqli_query(
                $conn,
                "DELETE FROM carts WHERE id = $cartId"
            );

            http_response_code(400);

            echo json_encode([
                "message" => "productId and quantity are required"
            ]);

            exit;
        }


        $productId = (int) $product["productId"];

        $quantity = (int) $product["quantity"];


        if ($quantity <= 0) {

            mysqli_query(
                $conn,
                "DELETE FROM carts WHERE id = $cartId"
            );

            http_response_code(400);

            echo json_encode([
                "message" => "Quantity must be greater than 0"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | Check product exists
        |--------------------------------------------------------------------------
        */

        $checkProduct = mysqli_query(
            $conn,
            "SELECT id FROM products WHERE id = $productId"
        );

        if (!$checkProduct) {

            mysqli_query(
                $conn,
                "DELETE FROM cart_products WHERE cartId = $cartId"
            );

            mysqli_query(
                $conn,
                "DELETE FROM carts WHERE id = $cartId"
            );

            http_response_code(500);

            echo json_encode([
                "message" => "Database error"
            ]);

            exit;
        }


        if (mysqli_num_rows($checkProduct) === 0) {

            mysqli_query(
                $conn,
                "DELETE FROM cart_products WHERE cartId = $cartId"
            );

            mysqli_query(
                $conn,
                "DELETE FROM carts WHERE id = $cartId"
            );

            http_response_code(404);

            echo json_encode([
                "message" => "Product $productId not found"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | Insert cart product
        |--------------------------------------------------------------------------
        */

        $insertProduct = mysqli_query(
            $conn,
            "INSERT INTO cart_products
            (
                cartId,
                productId,
                quantity
            )
            VALUES
            (
                $cartId,
                $productId,
                $quantity
            )"
        );


        if (!$insertProduct) {

            mysqli_query(
                $conn,
                "DELETE FROM cart_products WHERE cartId = $cartId"
            );

            mysqli_query(
                $conn,
                "DELETE FROM carts WHERE id = $cartId"
            );

            http_response_code(500);

            echo json_encode([
                "message" => "Failed to add product to cart"
            ]);

            exit;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Return created cart
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            c.id,
            c.userId,
            c.date,
            cp.productId,
            cp.quantity
        FROM carts c
        LEFT JOIN cart_products cp
            ON c.id = cp.cartId
        WHERE c.id = $cartId
    ";

    $result = mysqli_query($conn, $sql);

    $cart = null;

    while ($row = mysqli_fetch_assoc($result)) {

        if ($cart === null) {

            $cart = [
                "id" => (int) $row["id"],
                "userId" => (int) $row["userId"],
                "date" => $row["date"],
                "products" => []
            ];
        }

        if ($row["productId"] !== null) {

            $cart["products"][] = [
                "productId" => (int) $row["productId"],
                "quantity" => (int) $row["quantity"]
            ];
        }
    }


    http_response_code(201);

    echo json_encode($cart, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| PUT - Update Cart
|--------------------------------------------------------------------------
*/

if ($method === "PUT") {

    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Cart ID is required"
        ]);

        exit;
    }


    $id = (int) $_GET["id"];


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
        !isset($data["date"]) ||
        !isset($data["products"])
    ) {

        http_response_code(400);

        echo json_encode([
            "message" => "userId, date and products are required"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Check cart
    |--------------------------------------------------------------------------
    */

    $check = mysqli_query(
        $conn,
        "SELECT id FROM carts
     WHERE id = $id
     AND userId = $loggedInUserId"
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
            "message" => "Cart not found"
        ]);

        exit;
    }




    $date = mysqli_real_escape_string(
        $conn,
        $data["date"]
    );


    /*
    |--------------------------------------------------------------------------
    | Update cart
    |--------------------------------------------------------------------------
    */

    $updateCart = mysqli_query(
        $conn,
        "UPDATE carts
     SET
        date = '$date'
     WHERE id = $id
     AND userId = $loggedInUserId"
    );


    if (!$updateCart) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update cart"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Delete old products
    |--------------------------------------------------------------------------
    */

    $deleteOldProducts = mysqli_query(
        $conn,
        "DELETE FROM cart_products
         WHERE cartId = $id"
    );


    if (!$deleteOldProducts) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to update cart products"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Insert new products
    |--------------------------------------------------------------------------
    */

    foreach ($data["products"] as $product) {

        $productId = (int) $product["productId"];

        $quantity = (int) $product["quantity"];


        mysqli_query(
            $conn,
            "INSERT INTO cart_products
            (
                cartId,
                productId,
                quantity
            )
            VALUES
            (
                $id,
                $productId,
                $quantity
            )"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Return updated cart
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            c.id,
            c.userId,
            c.date,
            cp.productId,
            cp.quantity
        FROM carts c
        LEFT JOIN cart_products cp
            ON c.id = cp.cartId
        WHERE c.id = $id
    ";


    $result = mysqli_query($conn, $sql);

    $cart = null;


    while ($row = mysqli_fetch_assoc($result)) {

        if ($cart === null) {

            $cart = [
                "id" => (int) $row["id"],
                "userId" => (int) $row["userId"],
                "date" => $row["date"],
                "products" => []
            ];
        }


        if ($row["productId"] !== null) {

            $cart["products"][] = [
                "productId" => (int) $row["productId"],
                "quantity" => (int) $row["quantity"]
            ];
        }
    }


    echo json_encode($cart, JSON_PRETTY_PRINT);

    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE - Delete Cart
|--------------------------------------------------------------------------
*/

if ($method === "DELETE") {

    if (!isset($_GET["id"])) {

        http_response_code(400);

        echo json_encode([
            "message" => "Cart ID is required"
        ]);

        exit;
    }


    $id = (int) $_GET["id"];


    /*
    |--------------------------------------------------------------------------
    | Check cart
    |--------------------------------------------------------------------------
    */

    $check = mysqli_query(
        $conn,
        "SELECT id FROM carts
     WHERE id = $id
     AND userId = $loggedInUserId"
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
            "message" => "Cart not found"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Delete cart products
    |--------------------------------------------------------------------------
    */

    $deleteProducts = mysqli_query(
        $conn,
        "DELETE FROM cart_products
         WHERE cartId = $id"
    );


    if (!$deleteProducts) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete cart products"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Delete cart
    |--------------------------------------------------------------------------
    */

    $deleteCart = mysqli_query(
        $conn,
        "DELETE FROM carts
     WHERE id = $id
     AND userId = $loggedInUserId"
    );


    if (!$deleteCart) {

        http_response_code(500);

        echo json_encode([
            "message" => "Failed to delete cart"
        ]);

        exit;
    }


    echo json_encode([
        "message" => "Cart deleted successfully",
        "id" => $id
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