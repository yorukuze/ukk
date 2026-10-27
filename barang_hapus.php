<?php
// Proses penghapusan data barang
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';
require_once __DIR__ . '/includes/crud.php';

cek_login();

// Pastikan hanya menerima request melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: barang.php');
    exit;
}

// Validasi token keamanan CSRF
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    set_flash('danger', 'Token keamanan tidak valid.');
    header('Location: barang.php');
    exit;
}

$id = $_POST['id'] ?? null;
$barang = validasi_id_barang($pdo, $id);

if (!$barang) {
    set_flash('danger', 'Data barang tidak ditemukan atau ID tidak valid.');
    header('Location: barang.php');
    exit;
}

if (hapus_barang($pdo, (int)$barang['id'])) {
    set_flash('success', 'Barang "' . $barang['nama'] . '" berhasil dihapus.');
} else {
    set_flash('danger', 'Gagal menghapus data barang.');
}

header('Location: barang.php');
exit;
