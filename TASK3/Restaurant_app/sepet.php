<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Veritabanı bağlantısını yapın
$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
try {
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit;
}

// Kullanıcının sepetindeki yemekleri kontrol et
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Sepet boşsa başlat
}

// Yemek sepete ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meal_id'])) {
    $meal_id = intval($_POST['meal_id']);
    if (!in_array($meal_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $meal_id; // Yemek sepete ekle
    }
    echo "Yemek sepete eklendi!";
}

// Yemek ID'lerini kullanarak yemek bilgilerini al
if (!empty($_SESSION['cart'])) {
    $meal_ids = implode(',', $_SESSION['cart']);
    $stmt = $connection->prepare("SELECT * FROM meals WHERE id IN ($meal_ids)");
    $stmt->execute();
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Sepetinizde hiç yemek yok.";
    exit;
}

// Sipariş verme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (empty($_SESSION['cart'])) {
        echo "Sepetinizde yemek yok, sipariş veremezsiniz.";
        exit;
    }

    $user_id = $_SESSION['user_id']; // Kullanıcı ID'sini oturumdan alın

    foreach ($_SESSION['cart'] as $meal_id) {
        $stmt = $connection->prepare('INSERT INTO orders (user_id, meal_id) VALUES (:user_id, :meal_id)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':meal_id', $meal_id);
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

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepet</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sepetiniz</h1>

    <?php if (!empty($meals)): ?>
        <table>
            <thead>
                <tr>
                    <th>Yemek Adı</th>
                    <th>Fiyat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meals as $meal): ?>
                    <tr>
                        <td><?= htmlspecialchars($meal['name']) ?></td>
                        <td><?= htmlspecialchars($meal['price']) ?> TL</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="sepet.php" method="POST">
            <button type="submit" name="place_order">Siparişi Ver</button>
        </form>
    <?php else: ?>
        <p>Sepetinizde hiç yemek yok.</p>
    <?php endif; ?>
    
    <a href="index.php">Ana Sayfaya Dön</a>
</body>
</html>

