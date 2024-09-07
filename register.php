<?php
include 'includes/db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'student';

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);

    echo "Kayıt başarıyla tamamlandı!";
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Kullanıcı adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit" name="register">Kayıt Ol</button>
</form>