<?php
session_start();

// CSRF token (her sayfa yüklenişinde yenilenir)
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Giriş Yap</h3>
                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    <form action="giris_islemi.php" method="POST" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <div class="mb-3">
                            <label>E-posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Giriş Yap</button>
                    </form>
                    <p class="mt-3 text-center">Hesabınız yok mu? <a href="kayit_ol.php">Kayıt Ol</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
