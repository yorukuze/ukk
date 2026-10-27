# Sistem Inventori Data Barang

Aplikasi web inventori data barang berbasis **PHP Native** dan **MySQL** yang dibangun sesuai spesifikasi teknis Uji Kompetensi Keahlian (UKK) Junior Web Developer.

Aplikasi ini mengutamakan kerapian arsitektur kode, keamanan standar industri (PDO prepared statement, CSRF, password hashing, validasi server-side), serta tampilan antarmuka yang bersih, responsif, dan bebas dari elemen kecerdasan buatan generik (*no AI slop*).

---

## Spesifikasi Teknis

- **Bahasa Pemrograman:** PHP 8.0+ (Native, tanpa framework)
- **Database:** MySQL / MariaDB (XAMPP)
- **Koneksi Database:** PHP Data Objects (PDO) dengan mode prepared statements
- **Frontend / Styling:** Bootstrap 5 (CDN) + Kustom CSS (Swiss Editorial Software Style)
- **Tipografi:** Google Fonts (Plus Jakarta Sans untuk UI, JetBrains Mono untuk kode/angka tabular)
- **Library PDF:** FPDF v1.86 Standalone (metrik font terintegrasi, tanpa dependensi folder eksternal)

---

## Fitur Aplikasi

1. **Autentikasi & Proteksi Akun:**
   - Registrasi pengguna baru dengan validasi username unik dan hashing password (`password_hash` BCRYPT).
   - Login dengan verifikasi `password_verify` dan regenerasi ID session (`session_regenerate_id`).
   - Proteksi sesi: semua halaman internal (`dashboard.php`, `barang.php`, `laporan.php`, dll.) otomatis memeriksa status login.
   - Logout bersih dengan pembersihan session data dan cookie session.

2. **Keamanan Aplikasi:**
   - **Cross-Site Request Forgery (CSRF):** Token keamanan unik berbasis session di setiap transaksi form POST.
   - **Cross-Site Scripting (XSS):** Sanitasi output menyeluruh menggunakan `htmlspecialchars`.
   - **SQL Injection Prevention:** Seluruh kueri database dieksekusi menggunakan PDO prepared statements.
   - **Pencegahan Resubmisi Form:** Menerapkan pola Post-Redirect-Get (PRG) disertai notifikasi flash message.

3. **Manajemen Data Barang (CRUD):**
   - **Create:** Tambah barang baru (kode unik, nama, kategori, harga satuan, stok). Validasi format input dan pencegahan kode barang ganda.
   - **Read:** Tabel data barang dengan nomor urut dinamis, kode bergaya tag monospaced, dan angka berformat rapi.
   - **Update:** Edit data barang dengan proteksi kode ganda (kecuali milik data yang sedang diedit).
   - **Delete:** Hapus data dengan metode aman POST, validasi token CSRF, dan konfirmasi interaktif.
   - **Search:** Fitur pencarian instan berdasarkan kode, nama barang, atau kategori.

4. **Dashboard Informasi:**
   - Ringkasan total varian jenis barang.
   - Akumulasi total seluruh unit fisik stok barang.
   - Kalkulasi grand total nilai aset inventaris (harga x stok).
   - Tabel pratinjau cepat untuk 5 data barang yang terakhir ditambahkan.

5. **Laporan & Cetak PDF:**
   - Tabel rekapitulasi inventaris lengkap dengan perhitungan subtotal per item dan total akumulasi.
   - Cetak dokumen resmi format PDF menggunakan FPDF standalone, lengkap dengan metadata tanggal cetak, penomoran halaman dinamis (*Page X of Y*), dan kolom pengesahan petugas.

6. **Antarmuka Responsif (Sidebar Navigation):**
   - **Desktop (>= 992px):** Sidebar tetap di sebelah kiri dengan penanda halaman aktif, profil petugas, dan tombol logout.
   - **Mobile (< 992px):** Sidebar otomatis berubah menjadi menu laci (*offcanvas drawer*) yang dapat dibuka melalui tombol menu atas berukuran sentuh 44px.

---

## Struktur Direktori

```text
ukk/
├── assets/
│   └── css/
│       └── style.css            # Styling kustom (sidebar, tabel, warna, responsif)
├── config/
│   └── koneksi.php              # Konfigurasi koneksi database PDO MySQL
├── fpdf/
│   └── fpdf.php                 # Library FPDF standalone
├── includes/
│   ├── auth.php                 # Session guard, CSRF token, flash message, fungsi escape
│   ├── crud.php                 # Logika query CRUD database
│   ├── footer.php               # Komponen footer dan penutup wrapper layout
│   ├── header.php               # Komponen header, meta tag, dan sidebar navigasi
│   ├── perhitungan.php          # Kalkulasi statistik inventaris dan format rupiah
│   └── validasi.php             # Validasi input data barang, user, dan ID
├── barang.php                   # Halaman data tabel barang & pencarian
├── barang_edit.php              # Form edit barang
├── barang_hapus.php             # Proses hapus barang (metode POST)
├── barang_tambah.php            # Form tambah barang
├── cetak_pdf.php                # Generator laporan dokumen PDF
├── dashboard.php                # Dashboard ringkasan inventaris
├── database.sql                 # Skema tabel database MySQL
├── index.php                    # Pengarah awal (redirect ke dashboard / login)
├── laporan.php                  # Halaman tinjauan laporan inventaris
├── login.php                    # Halaman masuk sistem
├── logout.php                   # Pembersihan sesi dan logout
├── .gitignore                   # Daftar berkas yang diabaikan oleh Git
└── README.md                    # Dokumentasi proyek
```

---

## Skema Database

File `database.sql` berisi struktur dua tabel utama:

```sql
CREATE DATABASE IF NOT EXISTS `db_inventori_barang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_inventori_barang`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `barang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL,
    `harga` DECIMAL(12, 2) NOT NULL,
    `stok` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Panduan Instalasi & Menjalankan

### 1. Salin Folder ke XAMPP
Pindahkan folder proyek `ukk` ke dalam direktori `htdocs`:
```text
C:\xampp\htdocs\ukk
```

### 2. Aktifkan XAMPP
Buka **XAMPP Control Panel**, kemudian jalankan modul:
- **Apache** (klik *Start*)
- **MySQL** (klik *Start*)

### 3. Import Database
- Buka browser dan buka `http://localhost/phpmyadmin/`.
- Klik tab menu **Import**.
- Klik **Choose File** / **Pilih Berkas**, lalu pilih file `database.sql` di dalam folder project.
- Klik tombol **Import** / **Kirim** di bagian bawah.

### 4. Buka Aplikasi di Browser
- Akses alamat:
  ```text
  http://localhost/ukk/
  ```
- Sistem akan mengarahkan ke halaman login.
- Klik menu **Daftar sekarang** (atau buka `http://localhost/ukk/register.php`) untuk registrasi akun baru.
- Masuk menggunakan akun tersebut untuk mengelola inventaris.

---

## Pengaturan Git & Pemutusan Repository

Jika proyek ini di-clone dari GitHub dan ingin diputuskan sambungannya agar menjadi folder mandiri:

- **Cara 1: Hapus folder .git (Putus Total)**
  Jalankan perintah berikut di terminal folder proyek:
  ```powershell
  Remove-Item -Recurse -Force .git
  ```

- **Cara 2: Hapus remote origin (Tetap simpan riwayat lokal)**
  ```bash
  git remote remove origin
  ```
