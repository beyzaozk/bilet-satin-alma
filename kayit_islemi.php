<?php
session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Geçersiz oturum isteği!";
    header("Location: kayit_ol.php");
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($full_name === '' || $email === '' || $password === '') {
    $_SESSION['error_message'] = "Tüm alanları doldurun.";
    header("Location: kayit_ol.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Geçersiz e-posta adresi.";
    header("Location: kayit_ol.php");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['error_message'] = "Şifre en az 6 karakter olmalıdır.";
    header("Location: kayit_ol.php");
    exit;
}

$stmt = $db->prepare("SELECT id FROM User WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['error_message'] = "Bu e-posta zaten kayıtlı.";
    header("Location: kayit_ol.php");
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$id = uniqid('', true);
$role = 'user';

$stmt = $db->prepare("INSERT INTO User (id, full_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$id, $full_name, $email, $hashed, $role]);

$_SESSION['user_id'] = $id;
$_SESSION['role'] = $role;

header("Location: index.php");
exit;
?>
