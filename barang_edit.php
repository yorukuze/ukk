<?php
// Halaman form edit data barang
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';
require_once __DIR__ . '/includes/crud.php';

cek_login();

// Validasi keberadaan ID barang dari query string
$id = $_GET['id'] ?? null;
$barang = validasi_id_barang($pdo, $id);

if (!$barang) {
    set_flash('danger', 'Data barang tidak ditemukan atau ID tidak valid.');
    header('Location: barang.php');
    exit;
}

$id = (int)$barang['id'];
$errors = [];
$input = [
    'kode'     => $barang['kode'],
    'nama'     => $barang['nama'],
    'kategori' => $barang['kategori'],
    'harga'    => $barang['harga'],
    'stok'     => $barang['stok'],
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
            if (cek_kode_duplikat($pdo, $input['kode'], $id)) {
                $errors[] = 'Kode barang "' . $input['kode'] . '" sudah digunakan oleh barang lain.';
            } else {
                $sukses = update_barang($pdo, $id, $input);
                if ($sukses) {
                    set_flash('success', 'Data barang berhasil diperbarui.');
                    header('Location: barang.php');
                    exit;
                } else {
                    $errors[] = 'Gagal memperbarui data ke database. Coba lagi.';
                }
            }
        }
    }
}

$page_title = 'Edit Barang';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="mb-0">Edit Data Barang</h5>
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

                <form method="POST" action="barang_edit.php?id=<?= $id ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="<?= e($input['kode']) ?>" required>
                        <div class="form-text">Kode unik maksimal 20 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= e($input['nama']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="<?= e($input['kategori']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga Satuan (Rp)</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= e((string)(int)$input['harga']) ?>" min="0" step="100" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="<?= e((string)(int)$input['stok']) ?>" min="0" step="1" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-3 pt-3 border-top">
                        <a href="barang.php" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
