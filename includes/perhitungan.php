<?php
// Fungsi kalkulasi statistik dan format angka

// Hitung total varian/jenis barang
function hitung_jumlah_barang(PDO $pdo): int {
    $stmt = $pdo->query('SELECT COUNT(*) FROM barang');
    return (int)$stmt->fetchColumn();
}

// Hitung akumulasi seluruh stok barang
function hitung_total_stok(PDO $pdo): int {
    $stmt = $pdo->query('SELECT COALESCE(SUM(stok), 0) FROM barang');
    return (int)$stmt->fetchColumn();
}

// Hitung total nilai finansial inventaris (harga * stok)
function hitung_total_nilai(PDO $pdo): float {
    $stmt = $pdo->query('SELECT COALESCE(SUM(harga * stok), 0) FROM barang');
    return (float)$stmt->fetchColumn();
}

// Format angka ke format mata uang Rupiah
function format_rupiah(float|int $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
