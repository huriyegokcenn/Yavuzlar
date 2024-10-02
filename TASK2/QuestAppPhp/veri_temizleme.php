<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$veritabaniDosyasi = 'C:\\xampp\\htdocs\\yeni_kalsor\\quiz.db';
if (!file_exists($veritabaniDosyasi)) {
    die('Veritabanı dosyası bulunamadı.');
}

$db = new SQLite3($veritabaniDosyasi);


$sorgu = $db->exec('DELETE FROM skor_tablosu');
if ($sorgu === false) {
    die('Skor tablosunu temizleme hatası: ' . $db->lastErrorMsg());
}


$sorgu = $db->exec('DELETE FROM yanitlanan_sorular');
if ($sorgu === false) {
    die('Yanıtlanan soruları temizleme hatası: ' . $db->lastErrorMsg());
}

echo 'Veriler başarıyla temizlendi.';
?>
