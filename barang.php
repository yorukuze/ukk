<?php
// Halaman utama data barang (tabel, pencarian, aksi)
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/crud.php';
require_once __DIR__ . '/includes/perhitungan.php';

cek_login();

$cari = trim($_GET['cari'] ?? '');
$daftar_barang = ambil_semua_barang($pdo, $cari);

$page_title = 'Data Barang';
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="mb-1">Data Barang</h4>
        <p class="text-muted mb-0">Kelola informasi barang, harga, dan ketersediaan stok.</p>
    </div>
    <div>
        <a href="barang_tambah.php" class="btn btn-primary-custom">Tambah Barang</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="barang.php" class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari kode, nama, atau kategori..." value="<?= e($cari) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </div>
            <?php if ($cari !== ''): ?>
                <div class="col-auto">
                    <a href="barang.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 130px;">Kode</th>
                        <th>Nama Barang</th>
                        <th style="width: 160px;">Kategori</th>
                        <th class="text-end" style="width: 150px;">Harga</th>
                        <th class="text-center" style="width: 100px;">Stok</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftar_barang)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <?= $cari !== '' ? 'Tidak ditemukan barang yang sesuai dengan pencarian.' : 'Belum ada data barang.' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($daftar_barang as $i => $row): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><code><?= e($row['kode']) ?></code></td>
                                <td class="fw-semibold"><?= e($row['nama']) ?></td>
                                <td><?= e($row['kategori']) ?></td>
                                <td class="text-end"><?= format_rupiah((float)$row['harga']) ?></td>
                                <td class="text-center"><?= number_format((int)$row['stok'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="barang_edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" action="barang_hapus.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang <?= e(addslashes($row['nama'])) ?>?');" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">
                                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
