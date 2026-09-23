<?php

// ================================
// Application Configuration
// ================================

define('SITE_NAME', 'FakeStore');

define('BASE_URL', 'http://localhost/fakestoreapi');


// ================================
// FakeStore API
// ================================

define('FAKESTORE_API_URL', 'https://fakestoreapi.com');


// ================================
// Session
// ================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}