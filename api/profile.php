    <?php

    require_once "../config/database.php";
    require_once "../middleware/auth.php";

    header("Content-Type: application/json");

    $user = authenticate();

    $userId = (int) $user->userId;

    $sql = "SELECT id, email, username, firstname, lastname, phone
            FROM users
            WHERE id = $userId
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (!$result) {

        http_response_code(500);

        echo json_encode([
            "message" => "Database error"
        ]);

        exit;
    }

    $data = mysqli_fetch_assoc($result);

    if (!$data) {

        http_response_code(404);

        echo json_encode([
            "message" => "User not found"
        ]);

        exit;
    }

    echo json_encode([
        "message" => "Protected profile accessed successfully",
        "user" => $data
    ]);