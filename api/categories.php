<?php

header("Content-Type: application/json");

require_once("../config/database.php");


/*
|--------------------------------------------------------------------------
| GET - Get all categories
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "message" => "Method not allowed"
    ]);

    exit;
}


$sql = "SELECT DISTINCT category FROM products";

$result = mysqli_query($conn, $sql);

if (!$result) {

    http_response_code(500);

    echo json_encode([
        "message" => "Database query failed"
    ]);

    exit;
}


$categories = [];

while ($row = mysqli_fetch_assoc($result)) {

    $categories[] = $row["category"];
}


echo json_encode($categories, JSON_PRETTY_PRINT);