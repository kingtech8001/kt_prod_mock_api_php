<?php

require_once "../config/db.php";

$result = $conn->query("SELECT id, password FROM users");

if (!$result) {
    die("Database query failed: " . $conn->error);
}

$updated = 0;

while ($user = $result->fetch_assoc()) {

    $id = $user['id'];
    $password = $user['password'];

    // Check whether password is already hashed
    $info = password_get_info($password);

    // algo = 0 means plain text password
    if ($info['algo'] === 0) {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "UPDATE users SET password = ? WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $hashedPassword,
            $id
        );

        if ($stmt->execute()) {
            $updated++;
        }

        $stmt->close();
    }
}

echo "Password migration completed. Updated users: " . $updated;    