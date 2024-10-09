<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    
    $database_path = "C:/xampp/htdocs/yeni/fooddelivery.db";
    try {
        $connection = new PDO("sqlite:$database_path");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['username'] === 'huriye') {
                header('Location: admin.php');
            } else {

                header('Location: index.php');
            }
            exit;
        } else {
            $error_message = "Kullanıcı adı veya şifre yanlış.";
        }
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Giriş Yap</h1>
    <form action="login.php" method="POST">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Giriş Yap</button>
    </form>
    <?php if (isset($error_message)): ?>
        <p style="color:red"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>
</body>
</html>
