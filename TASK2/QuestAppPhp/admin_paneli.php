<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbFile = 'C:\\xampp\\htdocs\\yeni_kalsor\\quiz.db'; 
$db = new SQLite3($dbFile);

$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$arama_terimi = isset($_POST['arama']) ? $_POST['arama'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['ekle'])) {
        $soru = $_POST['soru'] ?? '';
        $secenek1 = $_POST['secenek1'] ?? '';
        $secenek2 = $_POST['secenek2'] ?? '';
        $secenek3 = $_POST['secenek3'] ?? '';
        $secenek4 = $_POST['secenek4'] ?? '';
        $dogru_cevap = $_POST['dogru_cevap'] ?? '';

        $sorgu = $db->prepare('INSERT INTO sorular (soru, secenekler, dogru_secenek) VALUES (:soru, :secenekler, :dogru_secenek)');
        $secenekler = "A) $secenek1, B) $secenek2, C) $secenek3, D) $secenek4";
        $sorgu->bindValue(':soru', $soru, SQLITE3_TEXT);
        $sorgu->bindValue(':secenekler', $secenekler, SQLITE3_TEXT);
        $sorgu->bindValue(':dogru_secenek', $dogru_cevap, SQLITE3_INTEGER);

        if ($sorgu->execute()) {
            echo 'Soru başarıyla eklendi.';
        } else {
            echo 'Soru eklenirken bir hata oluştu.';
        }
    }

    if (isset($_POST['guncelle'])) {
        $soru_id = $_POST['soru_id'] ?? null;
        $soru = $_POST['soru'] ?? '';
        $secenek1 = $_POST['secenek1'] ?? '';
        $secenek2 = $_POST['secenek2'] ?? '';
        $secenek3 = $_POST['secenek3'] ?? '';
        $secenek4 = $_POST['secenek4'] ?? '';
        $dogru_cevap = $_POST['dogru_cevap'] ?? '';

        $sorgu = $db->prepare('UPDATE sorular SET soru = :soru, secenekler = :secenekler, dogru_secenek = :dogru_secenek WHERE id = :id');
        $secenekler = "A) $secenek1, B) $secenek2, C) $secenek3, D) $secenek4";
        $sorgu->bindValue(':soru', $soru, SQLITE3_TEXT);
        $sorgu->bindValue(':secenekler', $secenekler, SQLITE3_TEXT);
        $sorgu->bindValue(':dogru_secenek', $dogru_cevap, SQLITE3_INTEGER);
        $sorgu->bindValue(':id', $soru_id, SQLITE3_INTEGER);

        if ($sorgu->execute()) {
            echo 'Soru başarıyla güncellendi.';
        } else {
            echo 'Soru güncellenirken bir hata oluştu.';
        }
    }

    if (isset($_POST['sil'])) {
        $soru_id = $_POST['soru_id'];
        $sorgu = $db->prepare('DELETE FROM sorular WHERE id = :id');
        $sorgu->bindValue(':id', $soru_id, SQLITE3_INTEGER);
        if ($sorgu->execute()) {
            echo 'Soru başarıyla silindi.';
        } else {
            echo 'Soru silinirken bir hata oluştu.';
        }
    }
}

$sorgu_str = 'SELECT * FROM sorular';
if ($arama_terimi) {
    $arama_terimi = '%' . $arama_terimi . '%';
    $sorgu_str .= ' WHERE soru LIKE :arama_terimi';
}
$sorgu = $db->prepare($sorgu_str);
if ($arama_terimi) {
    $sorgu->bindValue(':arama_terimi', $arama_terimi, SQLITE3_TEXT);
}
$sorular = $sorgu->execute();

$soru_to_edit = null;
if ($edit_id) {
    $soru_to_edit_query = $db->prepare('SELECT * FROM sorular WHERE id = :id');
    $soru_to_edit_query->bindValue(':id', $edit_id, SQLITE3_INTEGER);
    $soru_to_edit_result = $soru_to_edit_query->execute();
    $soru_to_edit = $soru_to_edit_result ? $soru_to_edit_result->fetchArray(SQLITE3_ASSOC) : null;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffc0cb;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            background-color: #000;
            color: #fff;
            padding: 20px;
            margin: 0;
            font-size: 2.5rem;
            text-transform: uppercase;
        }
        h2 {
            color: #007bff;
        }
        form {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin: 10px auto;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .input-group {
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Admin Paneli</h1>

    <h2>Soruları Ara</h2>
    <form method="post">
        <input type="text" name="arama" placeholder="Soru ara...">
        <input type="submit" value="Ara">
    </form>

    <h2>Soru Ekle</h2>
    <form method="post">
        <label for="soru">Soru:</label>
        <input type="text" id="soru" name="soru" required>
        <div class="">
            <h2>Seçenekler</h2>
            <div class="input-group">
                <label for="secenek1">Seçenek 1:</label>
                <input type="text" id="secenek1" name="secenek1" required>
            </div>
            <div class="input-group">
                <label for="secenek2">Seçenek 2:</label>
                <input type="text" id="secenek2" name="secenek2" required>
            </div>
            <div class="input-group">
                <label for="secenek3">Seçenek 3:</label>
                <input type="text" id="secenek3" name="secenek3" required>
            </div>
            <div class="input-group">
                <label for="secenek4">Seçenek 4:</label>
                <input type="text" id="secenek4" name="secenek4" required>
            </div>
        </div>
        <label for="dogru_cevap">Doğru Cevap:</label>
        <select id="dogru_cevap" name="dogru_cevap">
            <option value="1">A</option>
            <option value="2">B</option>
            <option value="3">C</option>
            <option value="4">D</option>
        </select>
        <input type="submit" name="ekle" value="Ekle">
    </form>

    <h2>Var Olan Sorular</h2>
    <ul>
        <?php while ($row = $sorular->fetchArray(SQLITE3_ASSOC)): ?>
            <li>
                <strong><?php echo $row['soru']; ?></strong>
                <p><?php echo $row['secenekler']; ?></p>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="soru_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="sil" value="Sil">
                </form>
                <a href="?edit=<?php echo $row['id']; ?>">Düzenle</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Çıkış Yap</h2>
    <form method="post" action="logout.php">
        <input type="submit" value="Çıkış Yap">
    </form>
</body>
</html>
