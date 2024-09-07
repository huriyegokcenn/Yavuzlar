<?php
try {
    $pdo = new PDO("sqlite:".__DIR__."/../db/database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Veritabanına bağlanılamadı: " . $e->getMessage());
}
?>