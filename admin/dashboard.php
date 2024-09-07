<?php
include '../includes/auth.php';
requireAdmin();

echo "Hoş geldin Admin!";
?>

<a href="add_question.php">Soru Ekle</a>
<a href="update_question.php">Soruyu Güncelle</a>
<a href="delete_question.php">Soruyu Sil</a>