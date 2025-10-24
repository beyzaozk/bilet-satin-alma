<?php
session_start();
require __DIR__ . '/includes/db.php';

// CSRF kontrolü
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Geçersiz oturum isteği!";
    header("Location: giris.php");
    exit;
}

// Girdi temizleme
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Boş alan kontrolü
if ($email === '' || $password === '') {
    $_SESSION['error_message'] = "E-posta ve şifre boş olamaz.";
    header("Location: giris.php");
    exit;
}

// Giriş deneme limiti
$ip = $_SERVER['REMOTE_ADDR'];
$attempt_file = __DIR__ . "/attempts/".md5($ip).".json";
if (!is_dir(__DIR__ . "/attempts")) mkdir(__DIR__ . "/attempts", 0755, true);

$attempts = 0;
$last_time = 0;
if (file_exists($attempt_file)) {
    $data = json_decode(file_get_contents($attempt_file), true);
    $attempts = $data['attempts'] ?? 0;
    $last_time = $data['time'] ?? 0;
}

if ($attempts >= 5 && time() - $last_time < 600) { // 10 dakika
    $_SESSION['error_message'] = "Çok fazla başarısız deneme! Lütfen 10 dakika bekleyin.";
    header("Location: giris.php");
    exit;
}

// Kullanıcı sorgula
$stmt = $db->prepare("SELECT id, password, role FROM User WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();

    // Başarılı giriş → deneme sıfırla
    if (file_exists($attempt_file)) unlink($attempt_file);

    header("Location: index.php");
    exit;
} else {
    $attempts++;
    file_put_contents($attempt_file, json_encode(['attempts' => $attempts, 'time' => time()]));
    $_SESSION['error_message'] = "E-posta veya şifre hatalı! ($attempts/5)";
    header("Location: giris.php");
    exit;
}
?>
