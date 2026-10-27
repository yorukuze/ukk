# Aplikasi CRUD Data Barang (Sistem Inventori)

Aplikasi web inventori data barang berbasis **PHP Native** dan **MySQL** yang dirancang untuk kebutuhan Uji Kompetensi Keahlian (UKK) Junior Web Developer.

## Fitur Utama

- **Autentikasi & Keamanan:**
  - Registrasi user baru dengan validasi username unik dan password hash (`password_hash`).
  - Login dengan proteksi session (`session_regenerate_id`).
  - Proteksi CSRF token di setiap form POST.
  - Sanitasi input dan pencegahan XSS (`htmlspecialchars`).
  - Proteksi SQL Injection menggunakan PDO Prepared Statements.
  - Alur POST-Redirect-GET dengan notifikasi flash message.
  - Konfirmasi dialog sebelum menghapus data barang.
- **Manajemen Data Barang (CRUD):**
  - Tinjauan data dalam tabel dengan pencarian kata kunci (kode, nama, kategori).
  - Tambah data barang baru dengan validasi kode unik (mencegah kode duplikat).
  - Edit data barang dengan pengecualian validasi kode milik sendiri.
  - Hapus data barang dengan metode POST dan verifikasi ID valid.
- **Dashboard Ringkasan:**
  - Total varian/jenis barang.
  - Akumulasi seluruh unit stok barang.
  - Grand total nilai finansial inventaris (harga x stok).
  - Tabel tinjauan 5 barang yang terakhir ditambahkan.
- **Laporan & Cetak PDF:**
  - Tabel rekapitulasi inventaris lengkap beserta subtotal dan total aset.
  - Cetak dokumen laporan berformat PDF menggunakan library FPDF standalone.
- **Antarmuka & Navigasi:**
  - Sidebar navigasi responsif (drawer offcanvas di perangkat mobile).
  - Desain bersih bernuansa Swiss editorial software menggunakan Bootstrap 5 dan CSS kustom.
  - Tipografi rapi menggunakan Google Fonts (Plus Jakarta Sans dan JetBrains Mono).

## Struktur Folder

```text
ukk/
├── assets/
│   └── css/
│       └── style.css
├── config/
│   └── koneksi.php
├── fpdf/
│   └── fpdf.php
├── includes/
│   ├── auth.php
│   ├── crud.php
│   ├── footer.php
│   ├── header.php
│   ├── perhitungan.php
│   └── validasi.php
├── barang.php
├── barang_edit.php
├── barang_hapus.php
├── barang_tambah.php
├── cetak_pdf.php
├── dashboard.php
├── database.sql
├── index.php
├── laporan.php
├── login.php
├── logout.php
└── register.php
```

## Panduan Instalasi & Menjalankan

1. **Salin Folder Project:**
   Tempatkan folder proyek ke dalam direktori `htdocs` XAMPP:
   ```text
   C:\xampp\htdocs\ukk
   ```

2. **Jalankan Apache & MySQL:**
   Buka XAMPP Control Panel lalu klik **Start** pada modul **Apache** dan **MySQL**.

3. **Import Database:**
   - Buka browser dan akses `http://localhost/phpmyadmin/`.
   - Buat database atau langsung pilih menu **Import**.
   - Pilih file `database.sql` yang ada di dalam root folder project.
   - Klik **Import** / **Kirim**.

4. **Akses Aplikasi:**
   - Buka browser dan kunjungi:
     ```text
     http://localhost/ukk/
     ```
   - Klik **Daftar sekarang** untuk membuat akun pengguna pertama kali.
   - Login dengan akun yang baru dibuat untuk mulai mengelola data barang.
