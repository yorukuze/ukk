<?php
// Halaman registrasi akun baru
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$nama_lengkap = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Token keamanan tidak valid. Silakan muat ulang halaman.';
    } else {
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $konfirmasi = trim($_POST['konfirmasi_password'] ?? '');

        $errors = validasi_registrasi($pdo, $nama_lengkap, $username, $password, $konfirmasi);

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)');
            $stmt->execute([$nama_lengkap, $username, $hashed_password]);

            set_flash('success', 'Registrasi berhasil. Silakan login menggunakan akun baru Anda.');
            header('Location: login.php');
            exit;
        }
    }
}

$page_title = 'Registrasi Pengguna';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center mt-3">
    <div class="col-12 col-sm-10 col-md-8 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header text-center py-3">
                <h5 class="mb-0">Daftar Akun Baru</h5>
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

                <form method="POST" action="register.php">
                    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= e($nama_lengkap) ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= e($username) ?>" required>
                        <div class="form-text">Minimal 4 karakter (huruf, angka, atau underscore).</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Minimal 6 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Daftar</button>
                </form>

                <div class="text-center mt-3 pt-3 border-top">
                    <span class="text-muted">Sudah memiliki akun?</span>
                    <a href="login.php" class="text-decoration-none">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
