# Sistem Pencatatan Pesanan — Fix Advertising

Sistem informasi internal untuk pencatatan pesanan dan pengelolaan bahan baku, dibangun dengan Laravel 12 sesuai draft skripsi.

## 1. Persiapan

Pastikan sudah terinstall di komputer kamu:
- XAMPP (Apache + MySQL) — jalankan **Apache** dan **MySQL** dari XAMPP Control Panel
- PHP 8.2.4 (biasanya sudah satu paket dengan XAMPP, tapi pastikan `php -v` di terminal menunjukkan versi 8.2+)
- Composer 2.10.1
- VS Code (opsional, untuk edit kode)

## 2. Buat Database

1. Buka `http://localhost/phpmyadmin`
2. Klik **New**, buat database baru dengan nama `fixads_db`
3. Tidak perlu buat tabel manual — nanti dibuat otomatis lewat migration.

## 3. Setup Project

Salin folder project ini ke folder kerja kamu (boleh di luar `htdocs` XAMPP, karena Laravel punya web server sendiri lewat `artisan serve`).

Buka terminal/CMD, masuk ke folder project:

```bash
cd fixads
```

Install dependency PHP:

```bash
composer install
```

Salin file environment:

```bash
copy .env.example .env
```
*(di Mac/Linux pakai `cp .env.example .env`)*

Generate application key:

```bash
php artisan key:generate
```

Cek isi file `.env`, pastikan bagian database sudah sesuai dengan setup XAMPP kamu:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fixads_db
DB_USERNAME=root
DB_PASSWORD=
```
*(default XAMPP: username `root`, password kosong — sesuaikan jika kamu mengubahnya)*

## 4. Migrasi & Seed Database

Jalankan migration untuk membuat semua tabel:

```bash
php artisan migrate
```

Isi data awal (akun login + contoh data produk & bahan baku):

```bash
php artisan db:seed
```

Buat symbolic link untuk folder storage (supaya file desain yang diupload bisa diakses lewat browser):

```bash
php artisan storage:link
```

## 5. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser ke `http://localhost:8000`

## 6. Akun Login Default

Setelah `db:seed`, dua akun ini sudah tersedia:

| Role | Email | Password |
|---|---|---|
| CIO Marketing | marketing@fixadvertising.com | password123 |
| CIO Production | production@fixadvertising.com | password123 |

**Silakan ganti password ini setelah testing**, atau buat akun baru lewat `php artisan tinker`:

```php
\App\Models\User::create([
    'name' => 'Nama Kamu',
    'email' => 'email@kamu.com',
    'password' => \Hash::make('password_baru'),
    'role' => 'cio_marketing', // atau 'cio_production'
]);
```

## 7. Struktur Modul

**Modul Pemesanan**
- CIO Marketing: buat pesanan baru (nomor invoice otomatis, upload desain)
- CIO Production: lihat detail pesanan, isi kode teknisi, ubah status (Queue → Processing → Completed/Delayed), input pemakaian bahan baku (stok berkurang otomatis)

**Modul Bahan Baku**
- Monitoring Stok: kedua role bisa lihat (read-only untuk Production, Marketing bisa tambah data master)
- Permintaan Bahan: CIO Production ajukan ketika stok kurang
- Persetujuan: CIO Marketing approve/reject permintaan
- Bahan Masuk: CIO Marketing catat barang yang datang dari supplier (stok bertambah otomatis), bisa dikaitkan ke permintaan yang sudah disetujui

**Modul Data Produk**
- CIO Marketing kelola jenis produk yang ditawarkan (Neon Box, Neon Flex, dll) sebagai referensi saat membuat pesanan.

## 8. Catatan Penting

- Format nomor invoice: `INV` + bulan + tahun + urutan 3 digit (contoh: `INV062026001` untuk pesanan pertama di Juni 2026).
- Format nomor permintaan bahan: `PB` + bulan + tahun + urutan (contoh: `PB062026001`).
- Format nomor transaksi bahan masuk: `BM` + bulan + tahun + urutan (contoh: `BM062026001`).
- Stok bahan baku **tidak pernah dikurangi secara manual** — hanya berkurang otomatis saat CIO Production menyimpan data pemakaian bahan di form update status pesanan.
- Stok bahan baku **bertambah otomatis** saat CIO Marketing mencatat Bahan Masuk.
- Sistem ini **tidak menggunakan konsep BOM (Bill of Materials)** — karena produk bersifat custom, pemakaian bahan baku dicatat manual per pesanan oleh CIO Production, bukan dari resep/komposisi tetap per jenis produk.

## 9. Troubleshooting

**Error "could not find driver"** → pastikan ekstensi `pdo_mysql` aktif di `php.ini` (biasanya sudah aktif default di XAMPP).

**Error "Access denied for user 'root'"** → cek ulang `DB_USERNAME` dan `DB_PASSWORD` di `.env`, sesuaikan dengan konfigurasi MySQL XAMPP kamu.

**Gambar desain tidak muncul setelah upload** → pastikan sudah jalankan `php artisan storage:link`.

**Halaman blank/error 500** → jalankan `php artisan config:clear` lalu coba lagi, atau cek `storage/logs/laravel.log` untuk detail error.
