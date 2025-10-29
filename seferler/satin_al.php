<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

$tripId = $_GET['id'] ?? null;

if (!$tripId) {
    header("Location: ../index.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM Trip WHERE id = ?");
$stmt->execute([$tripId]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trip) {
    echo "<div class='alert alert-danger text-center mt-5'>Sefer bulunamadı.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];

    $ticketStmt = $db->prepare("
        INSERT INTO Tickets (id, trip_id, user_id, total_price, status, created_at)
        VALUES (:id, :trip_id, :user_id, :total_price, 'active', DATETIME('now'))
    ");

    $ticketStmt->execute([
        ':id' => uniqid('ticket_', true),
        ':trip_id' => $tripId,
        ':user_id' => $userId,
        ':total_price' => $trip['price']
    ]);

    $_SESSION['success'] = "Bilet başarıyla satın alındı!";
    header("Location: ../biletler/listele.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="fw-bold mb-3 text-center">🎫 Bilet Satın Al</h3>
        <p class="text-center text-muted mb-4">Aşağıdaki seferi onaylayarak biletinizi oluşturabilirsiniz.</p>

        <div class="border rounded p-3 mb-4 bg-light">
            <h5><?= htmlspecialchars($trip['from_city']) ?> → <?= htmlspecialchars($trip['to_city']) ?></h5>
            <p class="mb-1"><strong>Tarih:</strong> <?= htmlspecialchars($trip['date']) ?></p>
            <p class="mb-1"><strong>Fiyat:</strong> <?= htmlspecialchars($trip['price']) ?> ₺</p>
            <p class="mb-0"><strong>Süre:</strong> <?= htmlspecialchars($trip['duration']) ?> saat</p>
        </div>

        <form method="POST">
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg px-4">Bileti Satın Al</button>
                <a href="../seferler/detay.php?id=<?= $trip['id'] ?>" class="btn btn-outline-secondary btn-lg px-4 ms-2">Geri Dön</a>
            </div>
        </form>
    </div>
</div>
