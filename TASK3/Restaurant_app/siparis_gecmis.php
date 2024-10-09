<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
try {
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $connection->prepare("SELECT * FROM order_history WHERE username = :username");
    $stmt->execute(['username' => $_SESSION['username']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Geçmişi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sipariş Geçmişi</h1>
    <table>
        <tr>
            <th>Yemek Adı</th>
            <th>Fiyat</th>
            <th>Tarih</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= htmlspecialchars($order['meal_name']) ?></td>
            <td><?= htmlspecialchars($order['price']) ?> TL</td>
            <td><?= htmlspecialchars($order['date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php">Ana Sayfaya Dön</a>
</body>
</html>
