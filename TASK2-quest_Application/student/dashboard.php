<?php
include '../includes/auth.php';
requireLogin();

echo "Hoş geldin, öğrenci!";
?>

<a href="solve_question.php">Soru Çöz</a>
<a href="../scoreboard.php">Puan Tablosu</a>