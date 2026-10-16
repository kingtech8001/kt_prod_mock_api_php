<?php

return [
    // Set JWT_SECRET in your hosting environment before deploying publicly.
    // The fallback is only for local development and must not be used in production.
    "secret" => getenv("JWT_SECRET") ?: "local-development-only-change-this-secret",
    "issuer" => "fakestoreapi",
    "expiry" => 3600
];
