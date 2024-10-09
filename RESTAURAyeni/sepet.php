<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
try {
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $meal_price = (float)$_POST['meal_price']; 


    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id' => $meal_id,
        'name' => $meal_name,
        'price' => $meal_price
    ];
    
    // Sipariş verildi mesajı
    echo "<p>Sepete eklendi!</p>";
}


$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Sepetiniz</h1>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <span><?php echo htmlspecialchars($item['name']); ?></span>
                <span><?php echo htmlspecialchars($item['price']); ?> TL</span>
            </div>
        <?php endforeach; ?>
        <div class="total">Toplam: <?php echo $total_price; ?> TL</div>
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>
    
    <form action="siparis.php" method="POST">
        <button type="submit" name="siparis_ver">Siparişi Ver</button>
    </form>
</div>
</body>
</html>
