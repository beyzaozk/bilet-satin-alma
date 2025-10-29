<?php
session_start();
require __DIR__ . '/includes/db.php';
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">🚌 Sefer Ara</h2>
        <p class="text-muted">Kalkış, varış ve tarih bilgilerini girerek uygun seferleri bulun.</p>
    </div>

    <div class="card shadow-sm p-4">
        <form action="seferler/listele.php" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kalkış Noktası</label>
                    <input type="text" name="from_city" class="form-control" placeholder="Örn: Ankara" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Varış Noktası</label>
                    <input type="text" name="to_city" class="form-control" placeholder="Örn: İstanbul" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tarih</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Ara</button>
                </div>
            </div>
        </form>
    </div>
</div>

