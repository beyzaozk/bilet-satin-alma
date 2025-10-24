<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

$userId = $_SESSION['user_id'];
$ticketId = $_GET['id'] ?? null;

if (!$ticketId) {
    header("Location: listele.php");
    exit;
}

// Bilet kullanıcıya mı ait?
$stmt = $db->prepare("SELECT * FROM Tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticketId, $userId]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    $_SESSION['error'] = "Bu bileti iptal etme yetkiniz yok.";
    header("Location: listele.php");
    exit;
}

// Bilet zaten iptal edilmiş mi?
if ($ticket['status'] !== 'active') {
    $_SESSION['error'] = "Bu bilet zaten iptal edilmiş.";
    header("Location: listele.php");
    exit;
}

// İptal et
$update = $db->prepare("UPDATE Tickets SET status = 'cancelled' WHERE id = ?");
$update->execute([$ticketId]);

$_SESSION['success'] = "Bilet başarıyla iptal edildi.";
header("Location: listele.php");
exit;
?>
