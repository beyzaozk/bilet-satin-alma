<?php
session_start();
require __DIR__ . '/includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $db->prepare("SELECT * FROM User WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    header("Location: index.php");
} else {
    echo "<div class='alert alert-danger'>E-posta veya şifre hatalı!</div>";
}
?>
