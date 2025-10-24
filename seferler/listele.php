<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/header.php';

$from = $_GET['from_city'] ?? '';
$to = $_GET['to_city'] ?? '';
$date = $_GET['date'] ?? '';

if (!$from || !$to || !$date) {
    echo "<div class='alert alert-warning text-center mt-5'>Lütfen tüm alanları doldurun.</div>";
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = $db->prepare("SELECT * FROM Trips WHERE from_city = ? AND to_city = ? AND date >= ?");
$stmt->execute([$from, $to, $date]);
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h3 class="text-center mb-4">🚍 Sefer Sonuçları</h3>

    <?php if (empty($trips)): ?>
        <div class="alert alert-info text-center">Bu kriterlere uygun sefer bulunamadı.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($trips as $trip): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($trip['from_city']) ?> → <?= htmlspecialchars($trip['to_city']) ?></h5>
                            <p class="card-text mb-1"><strong>Tarih:</strong> <?= htmlspecialchars($trip['date']) ?></p>
                            <p class="card-text mb-1"><strong>Fiyat:</strong> <?= htmlspecialchars($trip['price']) ?> ₺</p>
                            <p class="card-text mb-3"><strong>Süre:</strong> <?= htmlspecialchars($trip['duration']) ?> saat</p>

                            <div class="mt-auto d-flex justify-content-between">
                                <a href="detay.php?id=<?= $trip['id'] ?>" class="btn btn-outline-primary btn-sm">Detayları Gör</a>

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="satin_al.php?id=<?= $trip['id'] ?>" class="btn btn-success btn-sm">Bilet Al</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" onclick="alert('Lütfen giriş yapın.'); window.location.href='../giris.php';">
                                        Bilet Al
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
