<?php
include '../includes/db.php';
include '../includes/auth.php';
requireAdmin();

if (isset($_POST['add_question'])) {
    $question = $_POST['question'];
    $correct_answer = $_POST['correct_answer'];

    $stmt = $pdo->prepare("INSERT INTO questions (question, correct_answer) VALUES (?, ?)");
    $stmt->execute([$question, $correct_answer]);

    echo "Soru başarıyla eklendi!";
}
?>

<form method="post">
    <textarea name="question" placeholder="Soru metni" required></textarea>
    <input type="text" name="correct_answer" placeholder="Doğru cevap" required>
    <button type="submit" name="add_question">Soru Ekle</button>
</form>