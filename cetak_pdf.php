<?php
// Cetak dokumen laporan inventaris format PDF menggunakan FPDF
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/crud.php';
require_once __DIR__ . '/includes/perhitungan.php';
require_once __DIR__ . '/fpdf/fpdf.php';

cek_login();

$daftar_barang = ambil_semua_barang($pdo);
$total_stok = hitung_total_stok($pdo);
$total_nilai = hitung_total_nilai($pdo);
$petugas = $_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? 'Petugas';

class PDF_Laporan extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 7, 'LAPORAN DATA INVENTARIS BARANG', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(190, 6, 'Sistem Manajemen Inventaris - Uji Kompetensi Keahlian', 0, 1, 'C');
        $this->SetLineWidth(0.4);
        $this->Line(10, 24, 200, 24);
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(95, 10, 'Dicetak pada: ' . date('d-m-Y H:i') . ' WIB', 0, 0, 'L');
        $this->Cell(95, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF_Laporan('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Informasi metadata laporan
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(30, 5, 'Tanggal Cetak', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(80, 5, date('d F Y'), 0, 0);
$pdf->Cell(25, 5, 'Dicetak Oleh', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(45, 5, $petugas, 0, 1);
$pdf->Ln(2);

// Header Tabel
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(241, 245, 249);
$pdf->SetDrawColor(203, 213, 225);

$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Kode', 1, 0, 'C', true);
$pdf->Cell(55, 8, 'Nama Barang', 1, 0, 'L', true);
$pdf->Cell(30, 8, 'Kategori', 1, 0, 'L', true);
$pdf->Cell(25, 8, 'Harga (Rp)', 1, 0, 'R', true);
$pdf->Cell(15, 8, 'Stok', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Total (Rp)', 1, 1, 'R', true);

// Isi Tabel
$pdf->SetFont('Arial', '', 8.5);
$no = 1;

if (empty($daftar_barang)) {
    $pdf->Cell(190, 8, 'Belum ada data barang.', 1, 1, 'C');
} else {
    foreach ($daftar_barang as $row) {
        $subtotal = (float)$row['harga'] * (int)$row['stok'];

        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(25, 7, $row['kode'], 1, 0, 'C');
        $pdf->Cell(55, 7, substr($row['nama'], 0, 32), 1, 0, 'L');
        $pdf->Cell(30, 7, substr($row['kategori'], 0, 18), 1, 0, 'L');
        $pdf->Cell(25, 7, number_format((float)$row['harga'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(15, 7, number_format((int)$row['stok'], 0, ',', '.'), 1, 0, 'C');
        $pdf->Cell(30, 7, number_format($subtotal, 0, ',', '.'), 1, 1, 'R');
    }

    // Baris Total
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(248, 250, 252);
    $pdf->Cell(120, 8, 'Total Keseluruhan', 1, 0, 'R', true);
    $pdf->Cell(25, 8, '-', 1, 0, 'C', true);
    $pdf->Cell(15, 8, number_format($total_stok, 0, ',', '.'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, number_format($total_nilai, 0, ',', '.'), 1, 1, 'R', true);
}

// Bagian Pengesahan / Tanda Tangan
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(130, 5, '', 0, 0);
$pdf->Cell(60, 5, 'Mengetahui,', 0, 1, 'C');
$pdf->Cell(130, 5, '', 0, 0);
$pdf->Cell(60, 5, 'Penanggung Jawab Inventaris', 0, 1, 'C');
$pdf->Ln(18);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(130, 5, '', 0, 0);
$pdf->Cell(60, 5, '( ' . $petugas . ' )', 0, 1, 'C');

$pdf->Output('I', 'laporan_inventaris_barang.pdf');
