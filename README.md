# 💈 AD Barbershop Bandung (@ad_barber)
### Sistem Reservasi, Manajemen Antrean & Mini-CMS Web Berbasis Laravel

Aplikasi sistem web terpadu untuk **AD Barbershop Bandung** yang dirancang sebagai proyek akhir rekayasa perangkat lunak (Skripsi / Tugas Akhir). Sistem ini mencakup reservasi online *real-time* dengan mekanisme pencegahan jadwal ganda (*anti double-booking*), integrasi gateway pembayaran **Midtrans Snap**, peta lokasi interaktif **Mapbox GL JS**, manajemen antrean kasir *walk-in*, analitik bisnis berbasis **Chart.js**, buku kas & laporan keuangan (ekspor CSV/Excel), serta **Mini-CMS** kustomisasi tampilan landing page.

---

## 🚀 Fitur Utama Sistem

### A. Sisi Pelanggan (Public Customer Area)
1. **Dynamic Landing Page (`/`)**:
   - Tampilan responsif modern bernuansa *Light & Energetic* (aksen kuning cerah `#FFCC00`, hitam kontras `#18181B`, dan putih bersih).
   - Banner promo / pengumuman dinamis di topbar.
   - Hero banner dengan tagline resmi `#KamiSolusi`, jam operasional `Open Daily: 10:00 - 21:00 WIB`, serta widget rating resmi Google (4.9 ★).
   - Katalog Layanan & Pricelist transparan.
   - Lookbook *"Inspirasi Gaya Rambut"* portofolio kapster.
   - Peta interaktif **Mapbox GL JS** dengan deteksi tema otomatis (`light-v11` / `dark-v11`) & fallback Google Maps.
2. **Real-Time Self-Service Booking Form (Anti Double-Booking)**:
   - Pelanggan memilih Layanan, Kapster, Tanggal, dan Slot Jam.
   - Slot jam terisi otomatis dinonaktifkan (*disabled*) secara *real-time* via AJAX endpoint (`/api/available-slots`).
   - Kalkulasi otomatis total biaya dan integrasi pembayaran **Midtrans Snap (Sandbox Mode)**.
   - Tombol **Simulasi Bayar Lunas (Sandbox)** sebagai fallback pengujian offline tanpa perlu tunneling *ngrok*.
3. **Cek Tiket Digital & Reservasi (`/cek-pesanan`)**:
   - Pencarian instan berdasarkan Kode Booking (`ADB-XXXXXX`) atau Nomor WhatsApp.
   - Visual Stepper Progres: `Terdipesan ➔ Terbayar ➔ Di Tempat ➔ Selesai`.
   - Tombol cetak tiket / unduh format PDF ramah cetak.

### B. Sisi Pengelola (Admin Workspace Area - `/admin`)
1. **Autentikasi Aman (`/admin/login`)**: Login administrator terproteksi session dan role middleware.
2. **Dashboard & Analitik Bisnis (`/admin/dashboard`)**:
   - **4 KPI Cards**: Total Pesanan, Antrean Hari Ini, Tingkat Keterisian Kursi (*Occupancy Rate %*), Pendapatan Bersih Hari Ini & Bulan Ini (hanya menjumlahkan transaksi berstatus `paid` / `completed`).
   - **3 Visualisasi Chart.js**:
     - *Bar/Line Chart*: Tren Pendapatan & Kunjungan 6 Bulan Terakhir.
     - *Donut Chart*: Statistik Layanan Terpopuler.
     - *Bar Chart*: Statistik Jam Tersibuk (*Peak Hours* 10:00 - 21:00 WIB).
   - Tabel monitoring antrean hari ini.
3. **Manajemen Antrean & Input Walk-In (`/admin/bookings`)**:
   - Filter pencarian berdasarkan tanggal, kata kunci, status reservasi, dan status pembayaran.
   - Modal form **+ Tambah Pesanan Walk-In** untuk mencatat pelanggan offline/tunai langsung di kasir.
   - Pembaruan status cepat (*pending*, *arrived*, *completed*, *cancelled*).
4. **Laporan Keuangan & Buku Kas (`/admin/reports`)**:
   - Filter rentang tanggal fleksibel.
   - Rekapitulasi pendapatan tunai walk-in vs non-tunai online (QRIS).
   - Tombol **Ekspor CSV / Excel** dengan header baku berbahasa Indonesia.
   - Format cetak formal tanda tangan manager dan kasir (`/admin/reports/print`).
5. **Master Data Layanan & Kapster (`/admin/services`, `/admin/barbers`)**:
   - CRUD lengkap dengan nomor kursi, foto kapster, spesialisasi, dan sakelar status aktif/libur.
6. **Web Customization & Mini-CMS (`/admin/cms`)**:
   - **Tab 1 (Theme Manager)**: Color picker warna utama brand (`primary_color`), toggle mode tema (`light` / `dark`), uploader logo & banner hero.
   - **Tab 2 (Layout Configurator)**: Atur urutan seksi landing page (Naik/Turun) dan sakelar tampil/sembunyi.
   - **Tab 3 (Content Authoring & Gallery)**: Kelola teks hero, slogan, jam buka, Google rating, koordinat peta, banner promo, dan CRUD galeri inspirasi rambut.

---

## 🛠️ Tech Stack & Arsitektur
- **Backend**: Laravel 11/12 (PHP 8.3+), MVC Architecture, Eloquent ORM.
- **Database**: MySQL (`ad_barbershop`).
- **Frontend**: Laravel Blade, Tailwind CSS, Alpine.js, Chart.js, Mapbox GL JS.
- **Payment Gateway**: Midtrans Snap API (Sandbox Mode).
- **Bahasa Antarmuka**: Seluruh UI, pesan validasi, dan notifikasi menggunakan Bahasa Indonesia baku.

---

## 📦 Panduan Instalasi Lokal

### 1. Kloning Repository & Dependensi
```bash
git clone https://github.com/dwialfarizki/AD-BarberShop.git
cd AD-BarberShop
composer install
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan pengaturan database di file `.env` sudah mengarah ke MySQL:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ad_barbershop
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeding Data Uji Realistis
Jalankan migrasi database beserta data dummy realistis:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Menjalankan Server Lokal
```bash
php artisan serve
```
Buka peramban di: `http://127.0.0.1:8000`

---

## 🔐 Akun Login Demo Admin
- **URL Login**: `http://127.0.0.1:8000/admin/login`
- **Email**: `admin@adbarber.com`
- **Password**: `password`

---

## 🧪 Menjalankan Pengujian Otomatis
Seluruh alur sistem telah diuji menggunakan PHPUnit:
```bash
php artisan test --filter=AdBarberSystemTest
```
Semua 7 skenario pengujian (Rendering Landing Page, Cek Tiket, Endpoint AJAX Slot, Login Admin, Dashboard KPI, Laporan Keuangan CSV, dan Validasi Anti Double-Booking) lulus 100%.

---
*Dikembangkan dengan penuh dedikasi untuk AD Barbershop Bandung.*
