<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

$userId = $_SESSION['user_id'];

// Kullanıcının biletlerini çek
$stmt = $db->prepare("
    SELECT t.id as ticket_id, t.total_price, t.status, tr.from_city, tr.to_city, tr.date
    FROM Tickets t
    JOIN Trip tr ON t.trip_id = tr.id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$userId]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../includes/header.php';
?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="container mt-5">
    <h3 class="text-center mb-4">🎫 Satın Alınan Biletlerim</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (empty($tickets)): ?>
        <div class="alert alert-info text-center">Henüz hiç bilet satın almadınız.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Sefer</th>
                        <th>Tarih</th>
                        <th>Fiyat</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['from_city']) ?> → <?= htmlspecialchars($t['to_city']) ?></td>
                            <td><?= htmlspecialchars($t['date']) ?></td>
                            <td><?= htmlspecialchars($t['total_price']) ?> ₺</td>
                            <td>
                                <?php if ($t['status'] === 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">İptal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['status'] === 'active'): ?>
                                    <a href="iptal_et.php?id=<?= urlencode($t['ticket_id']) ?>" class="btn btn-danger btn-sm">İptal Et</a>
                                <?php endif; ?>
                                <a href="pdf_olustur.php?id=<?= urlencode($t['ticket_id']) ?>" class="btn btn-outline-primary btn-sm">PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
