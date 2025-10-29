<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();
require __DIR__ . '/../includes/fpdf.php';

$userId = $_SESSION['user_id'];
$ticketId = $_GET['id'] ?? null;

if (!$ticketId) {
    die("Geçersiz bilet ID");
}

$stmt = $db->prepare("
    SELECT t.id, t.total_price, t.status, t.created_at, 
           tr.from_city, tr.to_city, tr.date, tr.duration, tr.company_name,
           u.full_name, u.email
    FROM Tickets t
    JOIN Trip tr ON t.trip_id = tr.id
    JOIN User u ON t.user_id = u.id
    WHERE t.id = ? AND u.id = ?
");
$stmt->execute([$ticketId, $userId]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Bilet bulunamadı veya erişim yetkiniz yok.");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Bilet Bilgileri', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Ad Soyad: ' . $ticket['full_name'], 0, 1);
$pdf->Cell(0, 10, 'E-posta: ' . $ticket['email'], 0, 1);
$pdf->Ln(5);

$pdf->Cell(0, 10, 'Kalkış: ' . $ticket['from_city'], 0, 1);
$pdf->Cell(0, 10, 'Varış: ' . $ticket['to_city'], 0, 1);
$pdf->Cell(0, 10, 'Tarih: ' . $ticket['date'], 0, 1);
$pdf->Cell(0, 10, 'Süre: ' . $ticket['duration'] . ' saat', 0, 1);
$pdf->Cell(0, 10, 'Firma: ' . $ticket['company_name'], 0, 1);
$pdf->Ln(5);

$pdf->Cell(0, 10, 'Tutar: ' . $ticket['total_price'] . ' ₺', 0, 1);
$pdf->Cell(0, 10, 'Durum: ' . ucfirst($ticket['status']), 0, 1);
$pdf->Cell(0, 10, 'Satın Alma Tarihi: ' . $ticket['created_at'], 0, 1);

$pdf->Output('I', 'bilet_' . $ticket['id'] . '.pdf');
?>
