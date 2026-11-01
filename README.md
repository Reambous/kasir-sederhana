
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
- Upload gambar produk dengan **preview real-time** di form tambah/edit
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

### 8. Object Storage & Metadata Gambar
- File gambar (produk & avatar profil) disimpan di disk `public` dengan nama acak, bukan nama asli
- Setiap file tercatat di tabel **`objects`**: `key`, `original_name`, `mime_type`, `size`, `uploader_id`
- FK `gambar_object_id` di tabel `products` & `users` menggantikan path tekstual lama
- Service `App\Services\ObjectStorage` menangani upload dengan **kompensasi otomatis** (gagal simpan metadata = file ikut dibuang)
- **Lifecycle anti-orphan** berbasis event Eloquent:
  - hapus produk → gambar & metadata ikut terhapus
  - hapus user → avatar + semua gambar produk miliknya ikut terhapus
  - ganti gambar → objek lama dihapus setelah transaksi berhasil

---

## Role Pengguna & Akun Default

| Role | Nama | Email | Password |
|---|---|---|---|
| **Admin** | admin | admin@gmail.com | admin123 |
| **Gudang** (Warehouse) | haidar | haidar@gmail.com | haidar123 |
| **Kasir** (Cashier) | anas | anas@gmail.com | anas12345 |

---

## Screenshot

<img width="1888" height="1043" alt="image" src="https://github.com/user-attachments/assets/ec91ae08-57ed-43b1-81dc-0bf6a31f446b" />

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

## Testing

```bash
php artisan test
```

Menjalankan seluruh suite test (auth, profil, dsb.) — pastikan database testing sudah dikonfigurasi di `.env.testing`.

---

## License

Proyek ini menggunakan lisensi **MIT**.
