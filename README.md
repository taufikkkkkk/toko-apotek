# Apotek Sehat — E-Commerce & Manajemen Farmasi

Aplikasi web apotek online berbasis **Laravel** dan **Tailwind CSS** yang menggabungkan etalase belanja real-time untuk pembeli serta dashboard manajemen inventaris, resep, dan laporan penjualan untuk admin.

## Fitur Utama

**Halaman Pembeli (Storefront)**
- **Katalog & Pencarian Cerdas:** Live search autocomplete berdasarkan nama obat maupun keluhan, filter kategori, dan sortir harga.
- **Real-Time Cart Drawer:** Tambah, ubah jumlah (`+/-`), dan hapus produk langsung dari keranjang geser tanpa reload halaman (AJAX).
- **Checkout & Opsi Pengiriman:** Pilihan Kurir Instan, Reguler, atau Ambil di Apotek (gratis) dengan kalkulasi ongkir otomatis dan cetak Struk/Invoice + konfirmasi WhatsApp.
- **Tebus Resep Dokter:** Unggah foto resep dengan *live preview* yang tersimpan di *private storage*.
- **Lacak Pesanan & Favorit:** Cek progres pesanan real-time via nomor invoice, ulasan/rating produk, dan daftar obat tersimpan.

**Dashboard Admin**
- **Statistik & Grafik Pendapatan:** Ringkasan inventaris, peringatan obat hampir kadaluarsa (≤ 30 hari), dan grafik penjualan 7 hari terakhir (Chart.js).
- **Manajemen Penuh:** CRUD data obat + upload gambar, kelola status pesanan, verifikasi resep dokter private, dan Export Laporan Penjualan (CSV).

**Keamanan**
- Proteksi brute-force login & rate limiting endpoint sensitif.
- Transaksi checkout menggunakan database transaction + `lockForUpdate()` dan kode invoice acak 8 karakter.
- Penyimpanan foto resep di *private disk* khusus admin & HTTP Security Headers.

## Tech Stack
- **Backend:** PHP, Laravel
- **Frontend:** Blade, Tailwind CSS, Vanilla JS (Fetch API), Chart.js
- **Database:** MySQL / SQLite

## Cara Menjalankan Lokal
```bash
git clone [https://github.com/taufikkkkkk/toko-apotek.git](https://github.com/taufikkkkkk/toko-apotek.git)
cd toko-apotek
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan db:seed --class=ObatSeeder
php artisan storage:link
php artisan serve