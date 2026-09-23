<?php

class FakeStoreAPI
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = FAKESTORE_API_URL;
    }


    // ================================
    // Make GET Request
    // ================================

    private function get(string $endpoint): array
    {
        $url = $this->baseUrl . $endpoint;

        $response = file_get_contents($url);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);

        return is_array($data) ? $data : [];
    }


    // ================================
    // Get All Products
    // ================================

    public function getProducts(): array
    {
        return $this->get('/products');
    }


    // ================================
    // Get Single Product
    // ================================

    public function getProduct(int $id): array
    {
        return $this->get('/products/' . $id);
    }


    // ================================
    // Get Categories
    // ================================

    public function getCategories(): array
    {
        return $this->get('/products/categories');
    }


    // ================================
    // Get Products By Category
    // ================================

    public function getProductsByCategory(string $category): array
    {
        return $this->get(
            '/products/category/' . urlencode($category)
        );
    }


    // ================================
    // Get All Carts
    // ================================

    public function getCarts(): array
    {
        return $this->get('/carts');
    }


    // ================================
    // Get Single Cart
    // ================================

    public function getCart(int $id): array
    {
        return $this->get('/carts/' . $id);
    }


    // ================================
    // Get All Users
    // ================================

    public function getUsers(): array
    {
        return $this->get('/users');
    }


    // ================================
    // Get Single User
    // ================================

    public function getUser(int $id): array
    {
        return $this->get('/users/' . $id);
    }
}