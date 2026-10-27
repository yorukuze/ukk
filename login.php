<?php
// Halaman login pengguna
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$username_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Token keamanan tidak valid. Silakan muat ulang halaman.';
    } else {
        $username_input = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username_input === '' || $password === '') {
            $errors[] = 'Username dan password wajib diisi.';
        } else {
            $stmt = $pdo->prepare('SELECT id, username, password, nama_lengkap FROM users WHERE username = ?');
            $stmt->execute([$username_input]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

                set_flash('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = 'Username atau password yang dimasukkan salah.';
            }
        }
    }
}

$page_title = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center mt-4">
    <div class="col-12 col-sm-8 col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header text-center py-3">
                <h5 class="mb-0">Masuk ke Sistem</h5>
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

                <form method="POST" action="login.php">
                    <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= e($username_input) ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">Masuk</button>
                </form>

                <div class="text-center mt-3 pt-3 border-top">
                    <span class="text-muted">Belum memiliki akun?</span>
                    <a href="register.php" class="text-decoration-none">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
