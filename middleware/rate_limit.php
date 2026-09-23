<?php

function rateLimit($key, $maxRequests = 60, $windowSeconds = 60)
{
    $storageDir = __DIR__ . "/../storage/rate_limit";

    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $file = $storageDir . "/" . md5($key) . ".json";

    $now = time();

    if (file_exists($file)) {

        $data = json_decode(
            file_get_contents($file),
            true
        );

        if (!is_array($data)) {
            $data = [];
        }

    } else {
        $data = [];
    }

    // Remove requests outside the current window
    $data = array_filter(
        $data,
        function ($timestamp) use ($now, $windowSeconds) {
            return ($now - $timestamp) < $windowSeconds;
        }
    );

    if (count($data) >= $maxRequests) {

        http_response_code(429);

        header("Content-Type: application/json");

        echo json_encode([
            "message" => "Too many requests. Please try again later."
        ]);

        exit;
    }

    $data[] = $now;

    file_put_contents(
        $file,
        json_encode(array_values($data)),
        LOCK_EX
    );
}