<?php
// Halaman tinjauan laporan inventaris dan cetak PDF
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/crud.php';
require_once __DIR__ . '/includes/perhitungan.php';

cek_login();

$daftar_barang = ambil_semua_barang($pdo);
$jumlah_barang = hitung_jumlah_barang($pdo);
$total_stok = hitung_total_stok($pdo);
$total_nilai = hitung_total_nilai($pdo);

$page_title = 'Laporan Inventaris';
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="mb-1">Laporan Inventaris Barang</h4>
        <p class="text-muted mb-0">Rekapitulasi seluruh stok barang dan akumulasi nilai aset.</p>
    </div>
    <div>
        <a href="cetak_pdf.php" target="_blank" class="btn btn-primary-custom">Cetak PDF</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Total Jenis Barang</div>
                <div class="stat-value"><?= number_format($jumlah_barang, 0, ',', '.') ?></div>
                <small class="text-muted">Total item yang terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Akumulasi Total Stok</div>
                <div class="stat-value"><?= number_format($total_stok, 0, ',', '.') ?> Unit</div>
                <small class="text-muted">Akumulasi seluruh unit fisik</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-label mb-1">Grand Total Nilai Aset</div>
                <div class="stat-value"><?= format_rupiah($total_nilai) ?></div>
                <small class="text-muted">Kalkulasi total harga &times; stok</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-bordered table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 120px;">Kode</th>
                        <th>Nama Barang</th>
                        <th style="width: 150px;">Kategori</th>
                        <th class="text-end" style="width: 140px;">Harga Satuan</th>
                        <th class="text-center" style="width: 90px;">Stok</th>
                        <th class="text-end" style="width: 160px;">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftar_barang)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data barang untuk dilaporkan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftar_barang as $i => $row): ?>
                            <?php $subtotal = (float)$row['harga'] * (int)$row['stok']; ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><code><?= e($row['kode']) ?></code></td>
                                <td class="fw-semibold"><?= e($row['nama']) ?></td>
                                <td><?= e($row['kategori']) ?></td>
                                <td class="text-end"><?= format_rupiah((float)$row['harga']) ?></td>
                                <td class="text-center"><?= number_format((int)$row['stok'], 0, ',', '.') ?></td>
                                <td class="text-end"><?= format_rupiah($subtotal) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($daftar_barang)): ?>
                    <tfoot>
                        <tr class="fw-bold table-light">
                            <td colspan="5" class="text-end">Total Keseluruhan:</td>
                            <td class="text-center"><?= number_format($total_stok, 0, ',', '.') ?></td>
                            <td class="text-end"><?= format_rupiah($total_nilai) ?></td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
