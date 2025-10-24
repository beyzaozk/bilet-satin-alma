<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='alert alert-warning text-center mt-5'>Sefer bulunamadı.</div>";
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = $db->prepare("SELECT * FROM Trip WHERE id = ?");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trip) {
    echo "<div class='alert alert-danger text-center mt-5'>Sefer bilgisi bulunamadı.</div>";
    require __DIR__ . '/../includes/footer.php';
    exit;
}
?>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-3"><?= htmlspecialchars($trip['from_city']) ?> → <?= htmlspecialchars($trip['to_city']) ?></h3>
        <p><strong>Tarih:</strong> <?= htmlspecialchars($trip['date']) ?></p>
        <p><strong>Fiyat:</strong> <?= htmlspecialchars($trip['price']) ?> ₺</p>
        <p><strong>Süre:</strong> <?= htmlspecialchars($trip['duration']) ?> saat</p>
        <p><strong>Firma:</strong> <?= htmlspecialchars($trip['company_name'] ?? 'Bilinmiyor') ?></p>

        <div class="mt-3">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="satin_al.php?id=<?= $trip['id'] ?>" class="btn btn-success">Bilet Al</a>
            <?php else: ?>
                <button class="btn btn-secondary" onclick="alert('Lütfen giriş yapın.'); window.location.href='../giris.php';">
                    Bilet Al
                </button>
            <?php endif; ?>
            <a href="listele.php?from_city=<?= urlencode($trip['from_city']) ?>&to_city=<?= urlencode($trip['to_city']) ?>&date=<?= urlencode($trip['date']) ?>" class="btn btn-outline-primary">Geri Dön</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
