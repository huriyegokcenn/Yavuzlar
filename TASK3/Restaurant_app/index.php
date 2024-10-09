<?php
session_start();

$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
try {
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit;
}


$stmt = $connection->prepare("SELECT * FROM meals");
$stmt->execute();
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Yemekler</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Hoş geldiniz, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <a href="sepet.php">Sepet</a>
        <a href="logout.php">Çıkış Yap</a>
    <?php else: ?>
        <a href="login.php">Giriş Yap</a>
    <?php endif; ?>

    <div class="meal-list">
        <?php foreach ($meals as $meal): ?>
            <div class="meal-item">
                <h2><?= htmlspecialchars($meal['name']) ?></h2>
                <p>Fiyat: <?= htmlspecialchars($meal['price']) ?> TL</p>
                <form action="sepet.php" method="POST">
                    <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
                    <button type="submit">Sepete Ekle</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
