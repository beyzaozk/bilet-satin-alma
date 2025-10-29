<?php
session_start();
require __DIR__ . '/../includes/db.php';


$stmt = $db->prepare("SELECT * FROM Trips WHERE id = ?");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trip) {
    echo "<div class='alert alert-danger text-center mt-5'>Sefer bilgisi bulunamadı.</div>";
    exit;
}
?>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-3"><?= htmlspecialchars($trip['departure_city']) ?> → <?= htmlspecialchars($trip['destination_city']) ?></h3>
        <p><strong>Kalkış:</strong> <?= htmlspecialchars($trip['departure_time']) ?></p>
        <p><strong>Varış:</strong> <?= htmlspecialchars($trip['arrival_time']) ?></p>
        <p><strong>Fiyat:</strong> <?= htmlspecialchars($trip['price']) ?> ₺</p>
        <p><strong>Kapasite:</strong> <?= htmlspecialchars($trip['capacity']) ?></p>
        <p><strong>Firma:</strong> <?= htmlspecialchars($trip['company_name'] ?? 'Bilinmiyor') ?></p>

        <div class="mt-3">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="satin_al.php?id=<?= $trip['id'] ?>" class="btn btn-success">Bilet Al</a>
            <?php else: ?>
                <button class="btn btn-secondary" onclick="alert('Lütfen giriş yapın.'); window.location.href='../giris.php';">
                    Bilet Al
                </button>
            <?php endif; ?>
            <a href="listele.php?from_city=<?= urlencode($trip['departure_city']) ?>&to_city=<?= urlencode($trip['destination_city']) ?>&date=<?= urlencode(substr($trip['departure_time'],0,10)) ?>" class="btn btn-outline-primary">Geri Dön</a>
        </div>
    </div>
</div>
