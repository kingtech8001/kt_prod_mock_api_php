<?php

header("Content-Type: application/json");

require_once("../config/database.php");


/*
|--------------------------------------------------------------------------
| GET - Products by category
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "message" => "Method not allowed"
    ]);

    exit;
}


if (!isset($_GET['category'])) {

    http_response_code(400);

    echo json_encode([
        "message" => "Category is required"
    ]);

    exit;
}


$category = mysqli_real_escape_string(
    $conn,
    $_GET['category']
);


$sql = "SELECT * FROM products 
        WHERE category = '$category'";


$result = mysqli_query($conn, $sql);


if (!$result) {

    http_response_code(500);

    echo json_encode([
        "message" => "Database query failed"
    ]);

    exit;
}


$products = [];


while ($row = mysqli_fetch_assoc($result)) {

    $row["id"] = (int)$row["id"];
    $row["price"] = (float)$row["price"];

    $products[] = $row;
}


echo json_encode($products, JSON_PRETTY_PRINT);