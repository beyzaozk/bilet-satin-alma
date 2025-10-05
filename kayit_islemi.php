<?php
session_start();
require __DIR__ . '/includes/db.php';

// CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Geçersiz istek.');
}

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($full_name) || empty($email) || empty($password)) {
    $_SESSION['error'] = "Tüm alanları doldurmalısınız.";
    header("Location: kayit_ol.php");
    exit;
}

// Şifreyi hash ile sakla
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$id = uniqid('', true);
$role = 'user';
$company_id = null; 
$balance = 800;   

$stmt = $db->prepare("INSERT INTO User (id, full_name, email, role, password) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$id, $full_name, $email, $role, $hashed_password]);

    $_SESSION['user_id'] = $id;
    $_SESSION['role'] = $role;

    header("Location: index.php");
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    header("Location: kayit_ol.php");
    exit;
}
?>
