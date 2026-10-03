# Sistem Booking Servis AHASS (Astra Honda Authorized Service Station)

Aplikasi web modern untuk Sistem Booking Servis AHASS (Honda) berbasis **Laravel**, **MySQL**, dan interaksi frontend **SPA sederhana (jQuery + AJAX)** dengan validasi backend kuota slot servis secara real-time.

---

## 🚀 Fitur Utama

1. **Form Booking Interaktif (SPA)**:
   - Input Plat Nomor kendaraan dengan auto-uppercase (contoh: `B 1234 ABC`).
   - Nama Lengkap Pelanggan & Nomor WhatsApp/HP.
   - Pilihan Tipe Motor Honda dengan Quick Buttons (`Vario 160`, `Beat`, `PCX 160`, `Scoopy`, `Stylo 160`, dll).
   - Pemilih Tanggal & Jam Servis (`08:00`, `09:00`, `10:00`, `11:00`, `13:00`, `14:00`, `15:00`, `16:00`).
   - Pilihan Paket Servis Resmi Honda (Servis Lengkap / Tune Up, Servis Ringan, Servis CVT, Injeksi Cleaner, Ganti Oli Plus, dll).
   - Pilihan Sparepart Asli AHM (HGP - Honda Genuine Parts & AHM Oil) dengan estimasi total biaya otomatis (*live price calculation*).
   - Keluhan / Gejala motor yang dialami pelanggan.

2. **Validasi Kuota Slot Backend (Maksimal 3 Motor / Jam)**:
   - Setiap slot jam dibatasi **maksimal 3 kendaraan**.
   - Validasi backend ketat dengan `DB::transaction()` dan pessimistic lock (`lockForUpdate()`) untuk mencegah *race condition* dan *overbooking*.
   - Jika kuota slot sudah mencapai 3 motor, backend mengembalikan respon error status `422 (Unprocessable Entity)` via AJAX dengan pesan kesalahan yang informatif.
   - Di frontend, slot yang penuh ditandai merah dengan badge `PENUH (3/3)` dan tombol dinonaktifkan.

3. **Tabel List Booking dengan Filter Dinamis (Tanpa Reload)**:
   - Data dimuat secara asinkron via AJAX.
   - Filter Tanggal Servis.
   - Filter Status (Dikonfirmasi, Dalam Pengerjaan, Selesai, Dibatalkan).
   - Filter Paket Servis.
   - Pencarian Instan / Live Search (debounce) berdasarkan Plat Nomor, Nama Pemilik, atau Tipe Motor.
   - Modal Detail Booking lengkap dengan rincian biaya, daftar part, catatan pit, dan tombol cetak bukti booking.
   - Pembaruan status cepat (Confirmed -> In Progress -> Completed / Cancelled).
   - Pembatalan / penghapusan booking via SweetAlert confirmation.

4. **Monitor Slot Jam AHASS (Visual Pit Bay)**:
   - Tampilan visual beban antrean pit AHASS per slot jam dari pukul 08:00 hingga 16:00.
   - Progress bar kapasitas slot (`0/3`, `1/3`, `2/3`, `3/3`).
   - Rincian unit kendaraan dan plat nomor yang terdaftar di masing-masing slot.

---

## 🛠️ Arsitektur Teknis

- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL (`ahass_booking`)
- **Frontend**: Blade, Bootstrap 5.3, FontAwesome 6, Google Fonts (Plus Jakarta Sans)
- **Interaksi / SPA**: jQuery 3.7.1, AJAX, SweetAlert2
- **Branding**: Honda AHASS Red (`#D3122A`), Carbon Slate, Pure White, modern badge indicators

---

## 🗄️ Struktur Database & Relasi

1. **`service_packages`**:
   - `id`, `name`, `code`, `description`, `price`, `duration_minutes`, `is_active`, `timestamps`
2. **`service_parts`**:
   - `id`, `name`, `code`, `category`, `price`, `stock`, `is_active`, `timestamps`
3. **`bookings`**:
   - `id`, `booking_code`, `customer_name`, `customer_phone`, `plate_number`, `motorcycle_type`, `service_date`, `service_time`, `service_package_id`, `selected_parts` (JSON), `complaints`, `estimated_total_price`, `status`, `notes`, `timestamps`
   - Indeks gabungan `(service_date, service_time)` untuk kecepatan pengecekan kuota slot.

---

## 📡 Daftar Endpoint / API

| Method | Endpoint | Deskripsi |
|---|---|---|
| `GET` | `/` | Halaman utama SPA Booking AHASS |
| `GET` | `/bookings/slots?date=YYYY-MM-DD` | Ambil status ketersediaan semua slot jam pada tanggal tertentu |
| `GET` | `/bookings/data` | Ambil data list booking dengan filter `date`, `status`, `package_id`, `search` |
| `POST` | `/bookings` | Simpan pendaftaran booking baru (dengan validasi kuota slot maks 3) |
| `GET` | `/bookings/{id}` | Ambil rincian detail data booking & suku cadang |
| `PATCH` | `/bookings/{id}/status` | Perbarui status booking (confirmed, in_progress, completed, cancelled) |
| `DELETE` | `/bookings/{id}` | Hapus atau batalkan data booking |

---

## ⚙️ Panduan Menjalankan Aplikasi

1. **Pastikan MySQL aktif**:
   - Database name: `ahass_booking`
   - User: `root`, Password: `` (default Laragon)

2. **Migrasi & Seed Data**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

3. **Jalankan Web Server**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser pada: [http://127.0.0.1:8000](http://127.0.0.1:8000)
