<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/bilet_sistemi.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Veritabanı bağlantı hatası.');
}
?>
