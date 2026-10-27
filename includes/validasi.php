<?php
// Fungsi validasi input data dan pemeriksaan duplikasi

// Validasi input data barang
function validasi_input_barang(array $data): array {
    $errors = [];

    $kode = trim($data['kode'] ?? '');
    $nama = trim($data['nama'] ?? '');
    $kategori = trim($data['kategori'] ?? '');
    $harga = trim($data['harga'] ?? '');
    $stok = trim($data['stok'] ?? '');

    if ($kode === '') {
        $errors[] = 'Kode barang wajib diisi.';
    } elseif (strlen($kode) > 20) {
        $errors[] = 'Kode barang maksimal 20 karakter.';
    } elseif (!preg_match('/^[A-Za-z0-9\-_]+$/', $kode)) {
        $errors[] = 'Kode barang hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).';
    }

    if ($nama === '') {
        $errors[] = 'Nama barang wajib diisi.';
    } elseif (strlen($nama) > 100) {
        $errors[] = 'Nama barang maksimal 100 karakter.';
    }

    if ($kategori === '') {
        $errors[] = 'Kategori barang wajib diisi.';
    } elseif (strlen($kategori) > 50) {
        $errors[] = 'Kategori barang maksimal 50 karakter.';
    }

    if ($harga === '') {
        $errors[] = 'Harga barang wajib diisi.';
    } elseif (!is_numeric($harga) || (float)$harga < 0) {
        $errors[] = 'Harga barang harus berupa angka positif atau nol.';
    }

    if ($stok === '') {
        $errors[] = 'Stok barang wajib diisi.';
    } elseif (!filter_var($stok, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) && $stok !== '0') {
        $errors[] = 'Stok barang harus berupa bilangan bulat positif atau nol.';
    }

    return $errors;
}

// Periksa apakah kode barang sudah terdaftar di database
function cek_kode_duplikat(PDO $pdo, string $kode, ?int $ignore_id = null): bool {
    if ($ignore_id !== null) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM barang WHERE kode = ? AND id != ?');
        $stmt->execute([$kode, $ignore_id]);
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM barang WHERE kode = ?');
        $stmt->execute([$kode]);
    }
    return (int)$stmt->fetchColumn() > 0;
}

// Validasi input registrasi user baru
function validasi_registrasi(PDO $pdo, string $nama_lengkap, string $username, string $password, string $konfirmasi): array {
    $errors = [];

    $nama_lengkap = trim($nama_lengkap);
    $username = trim($username);

    if ($nama_lengkap === '') {
        $errors[] = 'Nama lengkap wajib diisi.';
    } elseif (strlen($nama_lengkap) > 100) {
        $errors[] = 'Nama lengkap maksimal 100 karakter.';
    }

    if ($username === '') {
        $errors[] = 'Username wajib diisi.';
    } elseif (strlen($username) < 4 || strlen($username) > 50) {
        $errors[] = 'Username harus terdiri dari 4 sampai 50 karakter.';
    } elseif (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
        $errors[] = 'Username hanya boleh terdiri dari huruf, angka, dan underscore (_).';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ((int)$stmt->fetchColumn() > 0) {
            $errors[] = 'Username sudah digunakan oleh akun lain.';
        }
    }

    if ($password === '') {
        $errors[] = 'Password wajib diisi.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password minimal terdiri dari 6 karakter.';
    }

    if ($password !== $konfirmasi) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    return $errors;
}

// Validasi dan ambil data barang berdasarkan ID
function validasi_id_barang(PDO $pdo, mixed $id): ?array {
    if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT * FROM barang WHERE id = ?');
    $stmt->execute([(int)$id]);
    $data = $stmt->fetch();

    return $data ?: null;
}
