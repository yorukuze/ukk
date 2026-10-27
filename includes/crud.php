<?php
// Operasi Create, Read, Update, Delete (CRUD) data barang

// Ambil semua data barang dengan opsi pencarian kata kunci
function ambil_semua_barang(PDO $pdo, string $keyword = ''): array {
    $keyword = trim($keyword);
    if ($keyword !== '') {
        $pattern = '%' . $keyword . '%';
        $stmt = $pdo->prepare('SELECT * FROM barang WHERE kode LIKE ? OR nama LIKE ? OR kategori LIKE ? ORDER BY id DESC');
        $stmt->execute([$pattern, $pattern, $pattern]);
    } else {
        $stmt = $pdo->query('SELECT * FROM barang ORDER BY id DESC');
    }
    return $stmt->fetchAll();
}

// Ambil satu data barang berdasarkan ID
function ambil_barang_by_id(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM barang WHERE id = ?');
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

// Tambah data barang baru
function tambah_barang(PDO $pdo, array $data): bool {
    $stmt = $pdo->prepare('INSERT INTO barang (kode, nama, kategori, harga, stok) VALUES (?, ?, ?, ?, ?)');
    return $stmt->execute([
        trim($data['kode']),
        trim($data['nama']),
        trim($data['kategori']),
        (float)$data['harga'],
        (int)$data['stok'],
    ]);
}

// Perbarui data barang yang sudah ada
function update_barang(PDO $pdo, int $id, array $data): bool {
    $stmt = $pdo->prepare('UPDATE barang SET kode = ?, nama = ?, kategori = ?, harga = ?, stok = ? WHERE id = ?');
    return $stmt->execute([
        trim($data['kode']),
        trim($data['nama']),
        trim($data['kategori']),
        (float)$data['harga'],
        (int)$data['stok'],
        $id,
    ]);
}

// Hapus data barang berdasarkan ID
function hapus_barang(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare('DELETE FROM barang WHERE id = ?');
    return $stmt->execute([$id]);
}
