<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbFile = 'C:\\xampp\\htdocs\\yeni_kalsor\\quiz.db'; 

$db = new SQLite3($dbFile);

$skor_sorgu = $db->prepare('
    SELECT kullanici_adi, 
           SUM(CASE WHEN secenek = dogru_secenek THEN 1 ELSE 0 END) AS puan 
    FROM yanitlanan_sorular
    JOIN sorular ON yanitlanan_sorular.soru_id = sorular.id
    GROUP BY kullanici_adi
');
$result = $skor_sorgu->execute();

if (!$result) {
    die('Sorgu çalıştırılırken bir hata oluştu: ' . $db->lastErrorMsg());
}

?> 
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Skor Tablosu</title>
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
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
        th, td {
            padding: 12px;
            border: 1px solid #00796b; 
            text-align: left;
        }
        th {
            background-color: #00796b;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f1f1f1; /* Açık gri */
        }
        a, input[type="submit"] {
            text-decoration: none;
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        a:hover, input[type="submit"]:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <h1>Skor Tablosu</h1>
    <table>
        <tr>
            <th>Kullanıcı Adı</th>
            <th>Puan</th>
        </tr>
        <?php 
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['kullanici_adi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['puan']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Puan bulunamadı.</td></tr>";
        }
        ?>
    </table>
    <a href="quiz_yarismasi.php">Quiz'e Dön</a>
    <form method="get" action="login.php" style="margin-top: 20px;">
        <input type="submit" value="Çıkış Yap">
    </form>
</body>
</html>
