<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbFile = 'C:\\xampp\\htdocs\\yeni_kalsor\\quiz.db'; 

session_start();
if (!isset($_SESSION['kullanici_adi'])) {
    die('Giriş yapmadınız!');
}

$kullanici_adi = $_SESSION['kullanici_adi'];
$db = new SQLite3($dbFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soru_id = $_POST['soru_id'] ?? null;
    $secenek = $_POST['secenek'] ?? null;

    if ($soru_id && $secenek) {
        $insert_sorgu = $db->prepare('
            INSERT OR IGNORE INTO yanitlanan_sorular (kullanici_adi, soru_id, secenek)
            VALUES (:kullanici_adi, :soru_id, :secenek)
        ');
        $insert_sorgu->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);
        $insert_sorgu->bindValue(':soru_id', $soru_id, SQLITE3_INTEGER);
        $insert_sorgu->bindValue(':secenek', $secenek, SQLITE3_INTEGER);
        $insert_sorgu->execute();
    }
}

$soru_sorgu = $db->prepare('
    SELECT s.id, s.soru, s.secenekler, s.dogru_secenek
    FROM sorular s
    LEFT JOIN yanitlanan_sorular y ON s.id = y.soru_id AND y.kullanici_adi = :kullanici_adi
    WHERE y.soru_id IS NULL
    ORDER BY RANDOM()
    LIMIT 1
');
$soru_sorgu->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);
$soru_result = $soru_sorgu->execute();

$soru = $soru_result->fetchArray(SQLITE3_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sınav</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            color: #333; 
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh; 
            text-align: center;
        }
        h1 {
            color: #00796b;
        }
        form {
            background: rgba(255, 255, 255, 0.9); 
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 300px;
            max-width: 100%;
        }
        p {
            font-size: 18px;
            margin-bottom: 15px;
        }
        input[type="radio"] {
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #00796b; 
            color: #fff; 
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #004d40; 
        }
    </style>
</head>
<body>
    <h1>Sınav</h1>
    <?php if ($soru): ?>
        <form method="post">
            <p><?php echo htmlspecialchars($soru['soru']); ?></p>
            <?php
            $secenekler = explode(', ', $soru['secenekler']);
            foreach ($secenekler as $index => $secenek) {
                echo "<input type='radio' name='secenek' value='" . ($index + 1) . "'> " . htmlspecialchars($secenek) . "<br>";
            }
            ?>
            <input type="hidden" name="soru_id" value="<?php echo $soru['id']; ?>">
            <input type="submit" value="Yanıtla ve Devam Et">
        </form>
    <?php else: ?>
        <p>Tüm soruları yanıtladınız. <a href="scoreboard.php">Skor Tablosu</a></p>
    <?php endif; ?>
</body>
</html>

