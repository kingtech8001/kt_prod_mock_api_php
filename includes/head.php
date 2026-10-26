<?php
require_once __DIR__ . '/../config/config.php';

// Base URL of the project (works on localhost/fakestoreapi AND on the live domain)
if (!isset($baseUrl)) {
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $baseUrl = rtrim(str_replace($docRoot, '', $projectRoot), '/');
}
$homeUrl = $baseUrl !== '' ? $baseUrl . '/' : '/';
$docsUrl = $baseUrl !== '' ? $baseUrl . '/docs' : '/docs';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MockAPI</title>
    <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/images/logo/mockapicv.png">
    <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/images/logo/mockapicv.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50 text-gray-900">
