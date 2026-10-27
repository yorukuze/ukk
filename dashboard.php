<?php
// Halaman dashboard ringkasan data inventaris
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/perhitungan.php';
require_once __DIR__ . '/includes/crud.php';

cek_login();

$jumlah_barang = hitung_jumlah_barang($pdo);
$total_stok = hitung_total_stok($pdo);
$total_nilai = hitung_total_nilai($pdo);

// Ambil 5 data barang terbaru untuk tinjauan cepat
$stmt = $pdo->query('SELECT * FROM barang ORDER BY id DESC LIMIT 5');
$barang_terbaru = $stmt->fetchAll();

$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="mb-1">Dashboard</h4>
        <p class="text-muted mb-0">Ringkasan data inventaris barang saat ini.</p>
    </div>
    <div>
        <a href="barang_tambah.php" class="btn btn-primary-custom">Tambah Barang</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Jumlah Jenis Barang</div>
                <div class="stat-value"><?= number_format($jumlah_barang, 0, ',', '.') ?></div>
                <small class="text-muted">Total item yang terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Total Stok Barang</div>
                <div class="stat-value"><?= number_format($total_stok, 0, ',', '.') ?></div>
                <small class="text-muted">Akumulasi seluruh unit fisik</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Total Nilai Inventaris</div>
                <div class="stat-value"><?= format_rupiah($total_nilai) ?></div>
                <small class="text-muted">Kalkulasi total harga &times; stok</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Barang Terakhir Ditambahkan</span>
        <a href="barang.php" class="text-decoration-none small">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($barang_terbaru)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data barang. Silakan tambahkan barang baru.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($barang_terbaru as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><code><?= e($row['kode']) ?></code></td>
                                <td class="fw-semibold"><?= e($row['nama']) ?></td>
                                <td><?= e($row['kategori']) ?></td>
                                <td class="text-end"><?= format_rupiah((float)$row['harga']) ?></td>
                                <td class="text-center"><?= number_format((int)$row['stok'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
