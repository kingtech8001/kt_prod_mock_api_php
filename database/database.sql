CREATE DATABASE IF NOT EXISTS fakestoreapi;

USE fakestoreapi;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    image VARCHAR(500)
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    products JSON NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name_firstName VARCHAR(100),
    name_lastName VARCHAR(100),
    city VARCHAR(100),
    street VARCHAR(255),
    number INT,
    zipcode VARCHAR(20),
    phone VARCHAR(50)
);

CREATE TABLE orders (

    id INT AUTO_INCREMENT PRIMARY KEY,

    userId INT NOT NULL,

    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    products JSON NOT NULL

);