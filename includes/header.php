<?php
// Layout header, navigasi sidebar, dan flash message
require_once __DIR__ . '/auth.php';

$current_page = basename($_SERVER['PHP_SELF']);
$page_title = $page_title ?? 'Sistem Inventori';
$flash = get_flash();

$logged_in = is_logged_in();
if ($logged_in) {
    $user_display_name = $_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
    $user_initial = mb_substr($user_display_name, 0, 1, 'UTF-8');
    $user_initial = strtoupper($user_initial);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> - Sistem Inventori</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="<?= $logged_in ? 'app-body' : 'guest-body' ?>">

<?php if ($logged_in): ?>
<div class="app-shell">
    <!-- Header toggle bar untuk layar mobile (< 992px) -->
    <header class="app-topbar d-lg-none">
        <button class="btn app-topbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Buka menu navigasi">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div class="app-topbar-brand">
            <span class="app-topbar-title">Sistem Inventori</span>
        </div>
        <div class="app-topbar-avatar" aria-hidden="true">
            <?= e($user_initial) ?>
        </div>
    </header>

    <!-- Sidebar navigasi responsive (drawer di mobile, sidebar tetap di desktop) -->
    <aside class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarTitle">
        <div class="app-sidebar-header">
            <a href="dashboard.php" class="app-sidebar-brand" id="appSidebarTitle">
                <span class="brand-text">Sistem Inventori</span>
            </a>
            <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Tutup menu navigasi"></button>
        </div>

        <div class="app-sidebar-content">
            <div class="sidebar-section-label">Navigasi Utama</div>
            <nav class="app-sidebar-nav" aria-label="Navigasi Utama">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
                            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                                <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                                <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($current_page, ['barang.php', 'barang_tambah.php', 'barang_edit.php']) ? 'active' : '' ?>" href="barang.php">
                            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                            <span>Data Barang</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'laporan.php') ? 'active' : '' ?>" href="laporan.php">
                            <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="app-sidebar-footer">
            <div class="sidebar-user-card">
                <div class="user-avatar" aria-hidden="true">
                    <?= e($user_initial) ?>
                </div>
                <div class="user-meta">
                    <span class="user-name text-truncate" title="<?= e($user_display_name) ?>"><?= e($user_display_name) ?></span>
                    <span class="user-status text-truncate">Petugas Inventori</span>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sidebar-logout" aria-label="Keluar dari akun">
                <svg class="logout-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Area konten utama -->
    <div class="app-main-wrapper">
        <main class="app-main-content">
            <div class="container-fluid px-3 px-lg-4 py-4">
                <?php if ($flash): ?>
                    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
                        <?= e($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

<?php else: ?>
    <!-- Navigasi ringkas untuk halaman autentikasi atau tamu -->
    <nav class="navbar navbar-expand guest-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="login.php">
                <span>Sistem Inventori</span>
            </a>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'login.php') ? 'active' : '' ?>" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'register.php') ? 'active' : '' ?>" href="register.php">Registrasi</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="guest-main py-4 py-md-5">
        <div class="container">
            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
                    <?= e($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
<?php endif; ?>
