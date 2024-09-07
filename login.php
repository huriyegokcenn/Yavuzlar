<?php
include 'includes/db.php';
include 'includes/auth.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: student/dashboard.php");
        }
    } else {
        echo "Yanlış kullanıcı adı veya şifre!";
    }
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Kullanıcı adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit" name="login">Giriş Yap</button>
</form>