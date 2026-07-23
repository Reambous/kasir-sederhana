<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="POS Inventory Logo">
</p>

<h1 align="center">POS Inventory System (POSSYS Enterprise)</h1>

<p align="center">
  Aplikasi Point of Sale (POS) dan Manajemen Inventaris berbasis web dengan Laravel, Livewire, dan Tailwind CSS.
</p>

---

## Deskripsi Proyek

**POS Inventory System (POSSYS Enterprise)** adalah aplikasi web untuk mengelola penjualan (POS) dan inventaris barang. Aplikasi ini mendukung tiga peran pengguna — **Admin**, **Gudang** (Warehouse), dan **Kasir** (Cashier) — masing-masing dengan hak akses yang sesuai.

Fitur utama meliputi terminal POS dengan keranjang belanja real-time, manajemen produk dan kategori, stock opname (audit stok fisik), riwayat transaksi, cetak struk thermal, serta manajemen pengguna.

---

## Tech Stack & Tools

| Komponen | Teknologi |
|---|---|
| **Backend Framework** | Laravel 12 |
| **PHP** | PHP ^8.2 |
| **Database** | MySQL (default), juga support PostgreSQL, SQLite, SQL Server |
| **Reactive Components** | Livewire 3 + Volt |
| **Frontend UI** | Tailwind CSS 3 |
| **JavaScript** | Alpine.js, Axios |
| **Build Tool** | Vite |
| **Auth Scaffolding** | Laravel Breeze |

---

## Fitur Utama

### 1. POS Kasir
- Katalog produk dengan pencarian (nama/barcode) dan filter kategori (tag)
- Grid produk dengan gambar, harga, dan indikator stok
- Keranjang belanja interaktif (tambah/kurang qty, input manual)
- Validasi stok — mencegah checkout jika stok tidak mencukupi
- Checkout dengan nama pembeli, metode bayar (Cash/Transfer), potongan harga, nominal diterima, dan hitung kembalian otomatis
- Cetak struk (format thermal printer 80mm) dengan `window.print()`
- Menggunakan **row locking** (`lockForUpdate`) untuk mencegah race condition

### 2. Manajemen Produk
- CRUD produk (nama, barcode, stok, harga beli, harga jual, gambar, kategori)
- Upload gambar produk
- Indikator stok color-coded (hijau >= 10, merah < 10)
- Pencarian produk (nama/barcode)
- Urut berdasarkan stok (stok terendah paling atas)

### 3. Kategori / Tag
- CRUD kategori produk
- Pencarian dan pagination

### 4. Stock Opname (Audit Stok Fisik)
- Buat session opname baru — otomatis snapshot stok semua produk
- Input jumlah fisik per produk dengan **auto-save** real-time
- Tambah kolom keterangan/catatan per produk
- Sinkronisasi produk baru ke session yang sedang berjalan
- Selesaikan session — stok aktual produk langsung terupdate
- Batalkan session (hapus session dan data)
- Arsip history opname dengan detail selisih stok

### 5. Riwayat Transaksi
- Tabel semua transaksi dengan pencarian
- Lihat detail invoice dan cetak ulang struk
- Hapus pesanan (stok otomatis dikembalikan)
- Hapus massal pesanan minggu ini
- Total pendapatan berjalan berdasarkan filter pencarian

### 6. Manajemen Pengguna (Admin Only)
- CRUD pengguna dengan role (Admin, Gudang, Kasir)
- Pencarian pengguna
- Tidak bisa menghapus akun sendiri

### 7. Dashboard
- Kartu statistik: total transaksi hari ini, total produk, produk stok menipis (< 10)
- Navigasi cepat berdasarkan role
- Log transaksi terbaru

---

## Role Pengguna & Akun Default

| Role | Nama | Email | Password |
|---|---|---|---|
| **Admin** | admin | admin@gmail.com | admin123 |
| **Gudang** (Warehouse) | haidar | haidar@gmail.com | haidar123 |
| **Kasir** (Cashier) | anas | anas@gmail.com | anas12345 |

---

## Screenshot

> _Screenshot akan ditambahkan. Upload screenshot ke folder `public/images/screenshots/` dan ganti `URL_SCREENSHOT` dengan URL gambar._

### Dashboard
![Dashboard](URL_SCREENSHOT_DASHBOARD)

Tampilan dashboard dengan statistik transaksi, total produk, dan stok menipis.

### POS Kasir
![POS Kasir](URL_SCREENSHOT_POS)

Terminal POS dengan katalog produk, keranjang belanja, dan form checkout.

### Manajemen Produk
![Manajemen Produk](URL_SCREENSHOT_PRODUK)

Daftar produk dengan indikator stok, pencarian, dan form tambah/edit produk.

### Stock Opname
![Stock Opname](URL_SCREENSHOT_OPNAME)

Session stock opname dengan input jumlah fisik dan auto-save.

### Riwayat Transaksi
![Riwayat Transaksi](URL_SCREENSHOT_ORDER)

Daftar transaksi dengan pencarian, cetak ulang struk, dan total pendapatan.

### Manajemen Pengguna
![Manajemen Pengguna](URL_SCREENSHOT_USER)

Manajemen user dengan role badge dan form CRUD.

---

## Cara Install

### Prerequisites
- PHP ^8.2
- Composer
- Node.js & npm
- MySQL / PostgreSQL / SQLite

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/username/pos-inventory.git
cd pos-inventory

# 2. Install dependencies PHP
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database (misal: pos_inventory) dan atur koneksi di .env
#    DB_CONNECTION=mysql
#    DB_DATABASE=pos_inventory
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migrasi dan seeder
php artisan migrate --seed

# 7. Install dependencies frontend
npm install

# 8. Build assets
npm run build

# 9. Buat storage link
php artisan storage:link

# 10. Jalankan development server
php artisan serve
```

Akses aplikasi di `http://localhost:8000`.

---

## License

Proyek ini menggunakan lisensi **MIT**.
