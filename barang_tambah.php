<?php
// Halaman form tambah data barang baru
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';
require_once __DIR__ . '/includes/crud.php';

cek_login();

$errors = [];
$input = [
    'kode'     => '',
    'nama'     => '',
    'kategori' => '',
    'harga'    => '',
    'stok'     => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Token keamanan tidak valid. Silakan coba kembali.';
    } else {
        $input['kode'] = trim($_POST['kode'] ?? '');
        $input['nama'] = trim($_POST['nama'] ?? '');
        $input['kategori'] = trim($_POST['kategori'] ?? '');
        $input['harga'] = trim($_POST['harga'] ?? '');
        $input['stok'] = trim($_POST['stok'] ?? '');

        $errors = validasi_input_barang($input);

        if (empty($errors)) {
            if (cek_kode_duplikat($pdo, $input['kode'])) {
                $errors[] = 'Kode barang "' . $input['kode'] . '" sudah terdaftar. Gunakan kode lain.';
            } else {
                $sukses = tambah_barang($pdo, $input);
                if ($sukses) {
                    set_flash('success', 'Barang baru berhasil ditambahkan.');
                    header('Location: barang.php');
                    exit;
                } else {
                    $errors[] = 'Gagal menyimpan data ke database. Coba lagi.';
                }
            }
        }
    }
}

$page_title = 'Tambah Barang';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="mb-0">Tambah Barang Baru</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="barang_tambah.php">
                    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="<?= e($input['kode']) ?>" placeholder="Contoh: BRG-001" required autofocus>
                        <div class="form-text">Kode unik maksimal 20 karakter (alfanumerik/strip).</div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= e($input['nama']) ?>" placeholder="Contoh: Monitor LED 24 Inch" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="<?= e($input['kategori']) ?>" placeholder="Contoh: Elektronik" required>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga Satuan (Rp)</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= e($input['harga']) ?>" min="0" step="100" placeholder="0" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="<?= e($input['stok']) ?>" min="0" step="1" placeholder="0" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-3 pt-3 border-top">
                        <a href="barang.php" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
