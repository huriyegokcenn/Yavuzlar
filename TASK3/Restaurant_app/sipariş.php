<?php
session_start();

// Veritabanı bağlantısını yapın
$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
$connection = new PDO("sqlite:$database_path");

// Kullanıcının sepetindeki yemekleri kontrol et
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Sepetinizde hiç yemek yok.";
    exit;
}

// Yemek ID'lerini kullanarak yemek bilgilerini al
$meal_ids = implode(',', array_map('intval', $_SESSION['cart']));
$stmt = $connection->prepare("SELECT * FROM meals WHERE id IN ($meal_ids)");
$stmt->execute();
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Kullanıcı ID'sini oturumdan alın

    foreach ($meals as $meal) {
        $stmt = $connection->prepare('INSERT INTO orders (user_id, meal_id) VALUES (:user_id, :meal_id)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':meal_id', $meal['id']);
        $stmt->execute();
    }

    // Sipariş başarılı mesajı
    $_SESSION['order_placed'] = "Siparişiniz başarıyla alındı!";
    // Sepeti temizle
    $_SESSION['cart'] = [];
    header('Location: index.php');
    exit();
}
?>
