<?php
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT * FROM questions 
    WHERE id NOT IN (
        SELECT question_id FROM submissions WHERE user_id = ?
    )
");
$stmt->execute([$user_id]);
$questions = $stmt->fetchAll();

if (!$questions) {
    echo "Çözecek soru kalmadı.";
} else {
    // Soru gösterme ve çözüm gönderme işlemleri burada olacak
}
?>
