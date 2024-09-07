<?php
include 'includes/db.php';

$stmt = $pdo->query("
    SELECT u.username, COUNT(s.id) AS score 
    FROM users u
    JOIN submissions s ON u.id = s.user_id
    WHERE s.is_correct = 1
    GROUP BY u.username
    ORDER BY score DESC
");
$scores = $stmt->fetchAll();
?>

<h2>Öğrenci Puanları</h2>
<ul>
<?php foreach ($scores as $score): ?>
    <li><?php echo $score['username']; ?>: <?php echo $score['score']; ?> puan</li>
<?php endforeach; ?>
</ul>