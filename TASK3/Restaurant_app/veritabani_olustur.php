<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";

try {
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kullanıcılar tablosunu sil
    $connection->exec("DROP TABLE IF EXISTS users");

    // Tabloları oluştur
    $connection->exec("CREATE TABLE IF NOT EXISTS firms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS restaurants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        firm_id INTEGER NOT NULL,
        FOREIGN KEY (firm_id) REFERENCES firms(id)
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS meals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL CHECK (price >= 0),
        restaurant_id INTEGER NOT NULL,
        image_url TEXT DEFAULT '',
        discount_price REAL DEFAULT 0 CHECK (discount_price >= 0 AND discount_price <= price),
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        meal_id INTEGER NOT NULL,
        username TEXT NOT NULL,
        comment TEXT NOT NULL,
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 10),
        FOREIGN KEY (meal_id) REFERENCES meals(id)
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL,
        balance REAL DEFAULT 0,
        deleted INTEGER DEFAULT 0,
        banned INTEGER DEFAULT 0
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS order_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        meal_id INTEGER NOT NULL,
        meal_name TEXT NOT NULL,
        note TEXT DEFAULT '',
        price REAL NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        username TEXT NOT NULL,
        purchased INTEGER DEFAULT 0,
        status TEXT DEFAULT 'hazırlanıyor',
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (meal_id) REFERENCES meals(id)
    )");

    $connection->exec("CREATE TABLE IF NOT EXISTS coupons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL UNIQUE,
        discount REAL NOT NULL,
        expiration_date DATE NOT NULL
    )");

    // Kullanıcıları ekle (şifreler 1234)
    $hashed_password = hash('sha256', '1234');

    $connection->exec("INSERT OR IGNORE INTO users (username, password, role, banned) VALUES
        ('gokcen', '$hashed_password', 'normal', 0),
        ('huriye', '$hashed_password', 'normal', 0),
        ('ahmet', '$hashed_password', 'normal', 0)
    ");

    echo "Veritabanı ve tablolar başarıyla oluşturuldu.";
    
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}

?>
