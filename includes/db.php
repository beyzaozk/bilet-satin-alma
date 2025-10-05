<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/bilet_sistemi.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Veritabanı bağlantı hatası: ' . $e->getMessage());
}
?>
