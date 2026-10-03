# AHASS Booking Service System

Aplikasi web sederhana untuk pendaftaran servis berkala motor Honda di bengkel resmi AHASS. Dibangun menggunakan Laravel dengan pendekatan SPA sederhana (jQuery AJAX) dan arsitektur Service-Repository.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Database:** MySQL
- **Frontend:** Blade, Bootstrap 5, FontAwesome, jQuery 3.7 (AJAX)
- **Design Pattern:** Service & Repository Pattern

---

## Cara Menjalankan Project

### 1. Prasyarat
Pastikan environment lokal sudah terinstall:
- PHP >= 8.2
- Composer
- MySQL (Laragon / XAMPP / Native)

### 2. Setup Database
Pastikan service MySQL aktif, lalu buat database baru:
```sql
CREATE DATABASE ahass_booking;
```

### 3. Konfigurasi Environment
Sesuaikan konfigurasi database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ahass_booking
DB_USERNAME=root
DB_PASSWORD=
```

Jika baru clone project dan belum ada file `.env`:
```bash
cp .env.example .env
composer install
php artisan key:generate
```

### 4. Migrasi & Seeder
Jalankan migrasi database beserta seeder data master dan sampel antrean:
```bash
php artisan migrate --seed
```
*(atau `php artisan migrate:fresh --seed` jika ingin reset data)*

### 5. Jalankan Server
```bash
php artisan serve
```
Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## Fitur & Ketentuan Teknis

1. **Form Booking Servis:**
   - Input data pelanggan (Nama, No. WhatsApp).
   - Input Plat Nomor (auto-uppercase) dan Tipe Motor Honda.
   - Pilihan paket servis resmi Honda (wajib dipilih, tidak tercentang default).
   - Pilihan suku cadang / oli asli AHM tambahan (opsional).
   - Estimasi biaya terhitung otomatis secara live.

2. **Validasi Kuota Slot (Backend & Frontend):**
   - Dibatasi maksimal 3 kendaraan per slot jam.
   - Pengecekan di backend menggunakan database transaction dan pessimistic locking (`lockForUpdate`) untuk mencegah overbooking saat banyak request masuk bersamaan.
   - Jika slot sudah terisi 3 unit, request via AJAX akan mengembalikan response HTTP `422` dan slot pada UI berstatus disabled/penuh.
   - *Catatan Pengujian:* Pada data seeder hari ini, slot jam `10:00` sengaja diisi 3 motor untuk menguji respon validasi slot penuh.

3. **Tabel List Booking (SPA / AJAX):**
   - Menampilkan daftar antrean servis tanpa reload halaman.
   - Filter berdasarkan tanggal servis, status booking, dan paket servis.
   - Live search (debounce) untuk plat nomor, nama pelanggan, atau tipe motor.
   - Modal detail booking beserta rincian part dan tombol cetak slip.
   - Update status pengerjaan (Confirmed, In Progress, Completed, Cancelled).
   - Fitur pembatalan/hapus antrean (kuota jam otomatis terbuka kembali).

4. **Monitor Slot Harian:**
   - Visualisasi kapasitas pit servis per jam operasional (08:00 - 16:00).
   - Progress bar kuota (0/3, 1/3, 2/3, 3/3) dan daftar kendaraan yang terdaftar di masing-masing jam.

---

## Struktur Arsitektur (Service & Repository)

Untuk menjaga controller tetap ringkas dan business logic terisolasi, project ini menerapkan Service-Repository pattern:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── BookingController.php          # Thin controller
│   └── Requests/
│       ├── StoreBookingRequest.php        # Form request validation
│       └── UpdateBookingStatusRequest.php
├── Repositories/
│   ├── Contracts/
│   │   ├── BookingRepositoryInterface.php
│   │   ├── ServicePackageRepositoryInterface.php
│   │   └── ServicePartRepositoryInterface.php
│   └── Eloquent/
│       ├── BookingRepository.php          # Query Eloquent & locking
│       ├── ServicePackageRepository.php
│       └── ServicePartRepository.php
├── Services/
│   └── BookingService.php                 # Core business logic (kuota, harga, validasi aturan AHASS)
└── Providers/
    └── AppServiceProvider.php             # Binding interface ke implementasi
```

---

## Endpoint API / AJAX

| Method | URL | Keterangan |
|---|---|---|
| `GET` | `/` | Halaman utama aplikasi (SPA) |
| `GET` | `/bookings/slots` | Ambil status ketersediaan kuota slot jam per tanggal |
| `GET` | `/bookings/data` | Ambil list booking dengan filter & search |
| `POST` | `/bookings` | Submit booking baru (validasi kuota slot maks 3) |
| `GET` | `/bookings/{id}` | Ambil data detail booking & sparepart |
| `PATCH` | `/bookings/{id}/status` | Update status servis |
| `DELETE` | `/bookings/{id}` | Hapus / batalkan booking |
