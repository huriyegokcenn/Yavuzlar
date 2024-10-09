<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";

try {
    
    $connection = new PDO("sqlite:$database_path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $connection->prepare('SELECT * FROM users WHERE deleted = 0 AND role != "admin" GROUP BY username'); // Kullanıcı adı ile grupla
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    $stmt = $connection->prepare('SELECT * FROM restaurants');
    $stmt->execute();
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $stmt = $connection->prepare('SELECT * FROM meals');
    $stmt->execute();
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $stmt = $connection->prepare('SELECT * FROM firms');
    $stmt->execute();
    $firms = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}


if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $connection->prepare('UPDATE users SET deleted = 1 WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


if (isset($_POST['undelete_user'])) {
    $user_id = $_POST['user_id'];
    $stmt = $connection->prepare('UPDATE users SET deleted = 0 WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


if (isset($_POST['add_meal'])) {
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $restaurant_id = $_POST['restaurant_id'];
    $image_url = $_POST['image_url'];

    $stmt = $connection->prepare('INSERT INTO meals (name, price, restaurant_id, image_url) VALUES (:name, :price, :restaurant_id, :image_url)');
    $stmt->bindParam(':name', $meal_name);
    $stmt->bindParam(':price', $meal_price);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


if (isset($_POST['edit_meal'])) {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $restaurant_id = $_POST['restaurant_id'];
    $image_url = $_POST['image_url'];

    $stmt = $connection->prepare('UPDATE meals SET name = :name, price = :price, restaurant_id = :restaurant_id, image_url = :image_url WHERE id = :id');
    $stmt->bindParam(':name', $meal_name);
    $stmt->bindParam(':price', $meal_price);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':id', $meal_id);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


if (isset($_POST['add_restaurant'])) {
    $restaurant_name = $_POST['restaurant_name'];
    $firm_id = $_POST['firm_id'];

    $stmt = $connection->prepare('INSERT INTO restaurants (name, firm_id) VALUES (:name, :firm_id)');
    $stmt->bindParam(':name', $restaurant_name);
    $stmt->bindParam(':firm_id', $firm_id);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


if (isset($_POST['add_firm'])) {
    $firm_name = $_POST['firm_name'];

    $stmt = $connection->prepare('INSERT INTO firms (name) VALUES (:name)');
    $stmt->bindParam(':name', $firm_name);
    $stmt->execute();
    header('Location: admin.php');
    exit();
}


$search_restaurant = '';
if (isset($_POST['search_restaurant'])) {
    $search_restaurant = $_POST['search_restaurant'];
    $stmt = $connection->prepare('SELECT * FROM restaurants WHERE name LIKE :search');
    $stmt->bindValue(':search', '%' . $search_restaurant . '%');
    $stmt->execute();
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$search_meal = '';
if (isset($_POST['search_meal'])) {
    $search_meal = $_POST['search_meal'];
    $stmt = $connection->prepare('SELECT * FROM meals WHERE name LIKE :search');
    $stmt->bindValue(':search', '%' . $search_meal . '%');
    $stmt->execute();
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Paneli</h1>

    <h2>Kullanıcılar</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Rol</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user">Banla</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="undelete_user">Kaldır</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Firma Ekle</h2>
    <form method="POST">
        <label for="firm_name">Firma Adı:</label>
        <input type="text" name="firm_name" required>
        <button type="submit" name="add_firm">Ekle</button>
    </form>

    <h2>Restoran Ekle</h2>
    <form method="POST">
        <label for="restaurant_name">Restoran Adı:</label>
        <input type="text" name="restaurant_name" required>
        <label for="firm_id">Firma Seç:</label>
        <select name="firm_id" required>
            <?php foreach ($firms as $firm): ?>
                <option value="<?php echo $firm['id']; ?>"><?php echo $firm['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add_restaurant">Ekle</button>
    </form>

    <h2>Yemek Ekle</h2>
    <form method="POST">
        <label for="meal_name">Yemek Adı:</label>
        <input type="text" name="meal_name" required>
        <label for="meal_price">Fiyat:</label>
        <input type="number" name="meal_price" step="0.01" required>
        <label for="restaurant_id">Restoran Seç:</label>
        <select name="restaurant_id" required>
            <?php foreach ($restaurants as $restaurant): ?>
                <option value="<?php echo $restaurant['id']; ?>"><?php echo $restaurant['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="image_url">Resim URL:</label>
        <input type="text" name="image_url">
        <button type="submit" name="add_meal">Ekle</button>
    </form>

    <h2>Yemekleri Düzenle</h2>
    <form method="POST">
        <label for="meal_id">Yemek Seç:</label>
        <select name="meal_id" required>
            <?php foreach ($meals as $meal): ?>
                <option value="<?php echo $meal['id']; ?>"><?php echo $meal['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="meal_name">Yeni Yemek Adı:</label>
        <input type="text" name="meal_name" required>
        <label for="meal_price">Yeni Fiyat:</label>
        <input type="number" name="meal_price" step="0.01" required>
        <label for="restaurant_id">Yeni Restoran Seç:</label>
        <select name="restaurant_id" required>
            <?php foreach ($restaurants as $restaurant): ?>
                <option value="<?php echo $restaurant['id']; ?>"><?php echo $restaurant['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="image_url">Yeni Resim URL:</label>
        <input type="text" name="image_url">
        <button type="submit" name="edit_meal">Düzenle</button>
    </form>

    <h2>Restoran Ara</h2>
    <form method="POST">
        <input type="text" name="search_restaurant" value="<?php echo $search_restaurant; ?>" placeholder="Restoran adı ara">
        <button type="submit">Ara</button>
    </form>

    <h2>Yemek Ara</h2>
    <form method="POST">
        <input type="text" name="search_meal" value="<?php echo $search_meal; ?>" placeholder="Yemek adı ara">
        <button type="submit">Ara</button>
    </form>

    <h2>Yemek Listesi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Yemek Adı</th>
            <th>Fiyat</th>
            <th>Restoran</th>
            <th>Resim URL</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($meals as $meal): ?>
        <tr>
            <td><?php echo $meal['id']; ?></td>
            <td><?php echo $meal['name']; ?></td>
            <td><?php echo $meal['price']; ?></td>
            <td><?php echo $meal['restaurant_id']; ?></td>
            <td><?php echo $meal['image_url']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="meal_id" value="<?php echo $meal['id']; ?>">
                    <button type="submit" name="delete_meal">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Restoran Listesi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Restoran Adı</th>
            <th>Firma ID</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($restaurants as $restaurant): ?>
        <tr>
            <td><?php echo $restaurant['id']; ?></td>
            <td><?php echo $restaurant['name']; ?></td>
            <td><?php echo $restaurant['firm_id']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                    <button type="submit" name="delete_restaurant">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<a href="login.php" class="button">Çıkış</a>

</body>
</html>

