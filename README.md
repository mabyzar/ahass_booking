# Sistem Booking Servis AHASS (Astra Honda Authorized Service Station)

Aplikasi web untuk **Sistem Booking Servis AHASS (Honda)** berbasis **Laravel**, **MySQL**, dan interaksi frontend **SPA sederhana (jQuery + AJAX)** dengan validasi backend kuota slot servis maksimal 3 kendaraan per slot jam serta arsitektur **Service & Repository Pattern**.

---

## 📋 Daftar Isi
1. [Prasyarat Sistem](#-prasyarat-sistem)
2. [Instruksi Cara Menjalankan Aplikasi](#-instruksi-cara-menjalankan-aplikasi)
3. [Panduan Pengujian Fitur](#-panduan-pengujian-fitur)
4. [Arsitektur & Pola Desain (Service & Repository)](#-arsitektur--pola-desain-service--repository)
5. [Daftar Endpoint API / AJAX](#-daftar-endpoint-api--ajax)
6. [Struktur Database & Seed Data](#-struktur-database--seed-data)

---

## 💻 Prasyarat Sistem

Sebelum menjalankan aplikasi, pastikan perangkat Anda telah terpasang:

- **PHP**: Versi `>= 8.2` (Aplikasi ini dikembangkan dan diuji pada PHP 8.4)
- **Composer**: Versi `>= 2.x`
- **Database Server**: **MySQL** `>= 8.0` / MariaDB (Dapat menggunakan Laragon, XAMPP, atau instalasi mandiri)
- **Ekstensi PHP**: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`
- **Web Browser**: Chrome, Edge, Firefox, atau browser modern lainnya

---

## 🚀 Instruksi Cara Menjalankan Aplikasi

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan aplikasi di lingkungan lokal:

### 1. Buka Terminal / PowerShell
Masuk ke direktori root proyek:
```bash
cd c:\laragon\www\takehometask
```

---

### 2. Pastikan Service Database MySQL Aktif
Pastikan MySQL service telah berjalan pada port `3306`.
- Jika menggunakan **Laragon**: Klik tombol **"Start All"** pada kontrol panel Laragon.
- Jika menggunakan **XAMPP**: Start modul **MySQL**.
- Atau jalankan lewat perintah CLI MySQL:
```bash
mysql -u root -e "SHOW DATABASES;"
```

---

### 3. Buat Database MySQL
Buat database baru bernama `ahass_booking` jika belum ada:
```sql
CREATE DATABASE IF NOT EXISTS ahass_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
*Atau via terminal:*
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ahass_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

### 4. Konfigurasi Environment (`.env`)
Pastikan file `.env` di root direktori telah terkonfigurasi untuk koneksi MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ahass_booking
DB_USERNAME=root
DB_PASSWORD=
```

*(Opsional: Jika file `.env` belum ada, salin dari `.env.example` dan generate application key)*:
```bash
cp .env.example .env
php artisan key:generate
```

---

### 5. Install Dependensi Composer (Jika Diperlukan)
Jika dependensi `vendor/` belum terpasang:
```bash
composer install
```

---

### 6. Jalankan Migrasi & Seeder Database
Jalankan migrasi tabel dan isi data awal (Paket Servis Resmi, Sparepart Asli Honda, dan sampel antrean booking):
```bash
php artisan migrate:fresh --seed
```

> **Catatan Seeder:**
> Seeder (`AhassDataSeeder`) akan otomatis membuat:
> - **6 Paket Servis Resmi Honda** (Servis Lengkap/Tune Up, Servis Ringan, Paket CVT, Injeksi Cleaner, Ganti Oli Plus, Rem).
> - **10 Sparepart & Oli Asli Honda (HGP & AHM Oil)** dengan harga katalog resmi.
> - **Sampel Booking Hari Ini** untuk mendemonstrasikan slot jam (contoh: slot `10:00` di-seed 3 motor agar berstatus **PENUH / FULL** untuk pengujian validasi backend).

---

### 7. Jalankan Web Server Laravel
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```
Secara default server akan berjalan pada:
```
http://127.0.0.1:8000
```

---

### 8. Akses Aplikasi di Browser
Buka browser dan akses alamat berikut:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🧪 Panduan Pengujian Fitur

Aplikasi ini menggunakan pendekatan **Single Page Application (SPA) sederhana** dengan **jQuery & AJAX** sehingga semua proses berjalan tanpa reload halaman:

### 1. Menguji Form Booking Baru
1. Buka tab **"Form Booking Baru"**.
2. Masukkan **Plat Nomor** (contoh: `B 1234 ABC` - otomatis kapital).
3. Masukkan **Nama Pelanggan** & **Nomor WhatsApp**.
4. Pilih **Tipe Motor Honda** menggunakan tombol cepat (contoh: `+ Vario 160` atau `+ Beat`).
5. Perhatikan **Pilihan Paket Servis**:
   - Paket servis **tidak tercentang secara default** (wajib dipilih oleh pengguna).
   - Jika tombol submit ditekan tanpa memilih paket, akan muncul pop-up peringatan **SweetAlert** dan pesan validasi merah.
6. Pilih salah satu paket servis dan centang suku cadang tambahan opsional (contoh: *AHM Oil MPX2*).
7. Perhatikan **Ringkasan Biaya** di sebelah kanan: total estimasi harga akan terhitung secara otomatis (*live price calculation*).

### 2. Menguji Validasi Kuota Slot Jam (Maksimal 3 Motor / Jam)
1. Pada tanggal hari ini, perhatikan chip slot jam:
   - Slot jam `10:00` berstatus **"PENUH (3/3)"** karena sudah terisi 3 unit kendaraan dari database seeder.
   - Chip slot jam `10:00` ditandai merah, dicoret, dan tidak dapat diklik.
2. Jika pengguna mencoba memesan slot yang sudah penuh (misal via bypass atau request bersamaan):
   - Backend di [BookingService.php](file:///c:/laragon/www/takehometask/app/Services/BookingService.php) melakukan pengecekan dengan *pessimistic lock* (`lockForUpdate()`).
   - Server menolak dan mengembalikan respon error status **HTTP 422 JSON**:
     `"Maaf, slot jam 10:00 pada tanggal YYYY-MM-DD sudah PENUH (maksimal 3 kendaraan). Silakan pilih slot jam lain atau tanggal berbeda."`
   - Frontend menampilkan modal error **SweetAlert** yang informatif.
3. Pilih slot yang masih tersedia (misal: `08:00`, `11:00`, atau `14:00`), lalu klik **"Konfirmasi Booking Servis"**. Booking akan berhasil dibuat dengan kode unik `AHASS-YYYYMMDD-XXXX`.

### 3. Menguji Tabel List Booking & Live Filter (AJAX)
1. Buka tab **"Daftar Booking Servis"**.
2. Uji fitur filter tanpa reload:
   - **Filter Tanggal**: Pilih tanggal tertentu untuk melihat jadwal servis pada hari tersebut.
   - **Filter Status**: Filter berdasarkan *Dikonfirmasi*, *Dalam Pengerjaan*, *Selesai*, atau *Dibatalkan*.
   - **Filter Paket Servis**: Filter berdasarkan jenis paket servis.
   - **Live Search**: Ketik plat nomor atau nama pelanggan pada kotak pencarian (pencarian instan dengan *debounce 300ms*).
3. **Modal Detail Booking**: Klik tombol mata (*eye*) untuk melihat rincian detail data booking, suku cadang, dan tombol cetak bukti booking.
4. **Ubah Status Cepat**: Klik tombol edit (*pen*) untuk mengubah status pengerjaan servis (misal: dari *Confirmed* ke *In Progress* atau *Completed*).
5. **Hapus / Batalkan Booking**: Klik tombol hapus (*trash*) dengan konfirmasi SweetAlert. Saat booking dibatalkan, kuota slot jam tersebut otomatis bertambah kembali.

### 4. Menguji Monitor Slot Jam AHASS (Visual Pit Bay)
1. Buka tab **"Monitor Slot Jam AHASS"**.
2. Anda dapat melihat visual beban antrean seluruh pit AHASS dari jam `08:00` s/d `16:00`.
3. Setiap slot menampilkan progress bar kapasitas (`0/3`, `1/3`, `2/3`, `3/3`), status ketersediaan, serta daftar plat nomor motor yang terdaftar di masing-masing jam.

---

## 🏛️ Arsitektur & Pola Desain (Service & Repository)

Aplikasi dibangun dengan memisahkan tanggung jawab kode (*Separation of Concerns*) dan *Clean Architecture*:

```
app/
├── Http/
│   ├── Controllers/
│   │   └── BookingController.php        # Thin Controller (hanya menangani HTTP request & response JSON/View)
│   └── Requests/
│       ├── StoreBookingRequest.php      # Validasi form booking (paket servis wajib dipilih, slot valid)
│       └── UpdateBookingStatusRequest.php # Validasi ubah status
├── Repositories/
│   ├── Contracts/
│   │   ├── BookingRepositoryInterface.php
│   │   ├── ServicePackageRepositoryInterface.php
│   │   └── ServicePartRepositoryInterface.php
│   └── Eloquent/
│       ├── BookingRepository.php        # Enkapsulasi query Eloquent, filtering, & locking
│       ├── ServicePackageRepository.php # Query data paket servis
│       └── ServicePartRepository.php    # Query data suku cadang & kalkulasi harga
├── Services/
│   └── BookingService.php               # Business Logic: Kuota slot maks 3, transaksi, duplikasi plat, kode booking
└── Providers/
    └── AppServiceProvider.php           # Dependency Injection bindings untuk repository
```

---

## 📡 Daftar Endpoint API / AJAX

| Method | Endpoint | Deskripsi |
|---|---|---|
| `GET` | `/` | Menampilkan halaman utama SPA Booking AHASS |
| `GET` | `/bookings/slots?date=YYYY-MM-DD` | Mengambil status kuota & ketersediaan seluruh slot jam pada tanggal tertentu |
| `GET` | `/bookings/data` | Mengambil data daftar booking dengan filter (`date`, `status`, `package_id`, `search`) |
| `POST` | `/bookings` | Mendaftarkan booking baru (dengan validasi slot maks 3 & paket wajib) |
| `GET` | `/bookings/{id}` | Mengambil detail lengkap booking beserta rincian sparepart |
| `PATCH` | `/bookings/{id}/status` | Memperbarui status booking (*confirmed*, *in_progress*, *completed*, *cancelled*) |
| `DELETE` | `/bookings/{id}` | Menghapus atau membatalkan data booking |

---

## 🗄️ Struktur Database & Seed Data

1. **`service_packages`**:
   - `id`, `name`, `code`, `description`, `price`, `duration_minutes`, `is_active`, `timestamps`
2. **`service_parts`**:
   - `id`, `name`, `code`, `category`, `price`, `stock`, `is_active`, `timestamps`
3. **`bookings`**:
   - `id`, `booking_code`, `customer_name`, `customer_phone`, `plate_number`, `motorcycle_type`, `service_date`, `service_time`, `service_package_id`, `selected_parts` (JSON), `complaints`, `estimated_total_price`, `status`, `notes`, `timestamps`
   - **Indeks**: `['service_date', 'service_time']` untuk performa tinggi saat pengecekan kuota antrean slot.
