<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AhassDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Paket Servis AHASS
        $packages = [
            [
                'name' => 'Servis Lengkap (Tune Up)',
                'code' => 'PKT-LENGKAP',
                'description' => 'Pembersihan injector/karburator, stel klep, periksa pengereman, kelistrikan, cek aki, ganti oli, dan 15 poin pemeriksaan menyeluruh.',
                'price' => 110000,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Servis Ringan / Berkala',
                'code' => 'PKT-RINGAN',
                'description' => 'Pemeriksaan standar: ganti oli, stel rem, cek rantai/roda, tekanan ban, dan lampu kelistrikan.',
                'price' => 65000,
                'duration_minutes' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Servis CVT Matic',
                'code' => 'PKT-CVT',
                'description' => 'Bongkar dan pembersihan pully primer & sekunder, pelumasan grease CVT khusus, cek roller, kampas ganda, dan v-belt.',
                'price' => 75000,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Injeksi Flush & Cleaner',
                'code' => 'PKT-INJEKSI',
                'description' => 'Pembersihan throttle body & nozzle injector dengan chemical ultrasonik khusus, kalibrasi sensor TP & reset ECM Honda.',
                'price' => 85000,
                'duration_minutes' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Ganti Oli + Safety Check',
                'code' => 'PKT-OLI-CHECK',
                'description' => 'Penggantian oli mesin & oli gardan matic plus cek 8 poin keselamatan standar Honda Astra.',
                'price' => 35000,
                'duration_minutes' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Perawatan Rem & Kaki-kaki',
                'code' => 'PKT-BRAKE',
                'description' => 'Pembersihan kaliper, bleeding minyak rem, penggantian kampas rem, dan setel laher roda.',
                'price' => 50000,
                'duration_minutes' => 35,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            \App\Models\ServicePackage::updateOrCreate(['code' => $pkg['code']], $pkg);
        }

        // 2. Sparepart Asli Honda (HGP - Honda Genuine Parts)
        $parts = [
            [
                'name' => 'AHM Oil MPX2 0.8L (Matic)',
                'code' => '08232-2MB-K0NZ1',
                'category' => 'Oli & Pelumas',
                'price' => 58000,
                'stock' => 120,
            ],
            [
                'name' => 'AHM Oil SPX2 0.8L (Full Synthetic Matic)',
                'code' => '08234-2MB-K0NZ1',
                'category' => 'Oli & Pelumas',
                'price' => 74000,
                'stock' => 80,
            ],
            [
                'name' => 'AHM Transmission Gear Oil 120ml (Oli Gardan)',
                'code' => '08268-M99-Z0NZ1',
                'category' => 'Oli & Pelumas',
                'price' => 18000,
                'stock' => 150,
            ],
            [
                'name' => 'Busi Honda NGK CPR9EA-9 (Vario/Beat/PCX)',
                'code' => '31919-K25-601',
                'category' => 'Pengapian',
                'price' => 28000,
                'stock' => 90,
            ],
            [
                'name' => 'Pad Set Front (Kampas Rem Cakram Depan AHM)',
                'code' => '06455-KVB-T01',
                'category' => 'Pengereman',
                'price' => 62000,
                'stock' => 60,
            ],
            [
                'name' => 'Shoe Set Brake (Kampas Rem Tromol Belakang)',
                'code' => '43130-KZL-930',
                'category' => 'Pengereman',
                'price' => 55000,
                'stock' => 55,
            ],
            [
                'name' => 'Drive Belt Kit (Vanbelt + Roller Set Honda)',
                'code' => '23100-K0J-N01',
                'category' => 'Transmisi CVT',
                'price' => 145000,
                'stock' => 35,
            ],
            [
                'name' => 'Element Cleaner / Filter Udara Viscous AHM',
                'code' => '17210-K0J-N00',
                'category' => 'Filter',
                'price' => 58000,
                'stock' => 70,
            ],
            [
                'name' => 'Coolant Radiator AHM Pre-mix 500ml',
                'code' => '08CLA-M99-001',
                'category' => 'Pendingin',
                'price' => 25000,
                'stock' => 45,
            ],
            [
                'name' => 'Brake Fluid DOT 4 AHM 50ml',
                'code' => '08200-DOT4-50',
                'category' => 'Pengereman',
                'price' => 15000,
                'stock' => 65,
            ],
        ];

        foreach ($parts as $part) {
            \App\Models\ServicePart::updateOrCreate(['code' => $part['code']], $part);
        }

        // 3. Sample Bookings (demonstrating slots today: 3 slots for 10:00 to test FULL slot)
        $today = date('Y-m-d');
        $pkgLengkap = \App\Models\ServicePackage::where('code', 'PKT-LENGKAP')->first();
        $pkgRingan = \App\Models\ServicePackage::where('code', 'PKT-RINGAN')->first();
        $pkgCvt = \App\Models\ServicePackage::where('code', 'PKT-CVT')->first();

        $partMpx2 = \App\Models\ServicePart::where('code', '08232-2MB-K0NZ1')->first();
        $partGardan = \App\Models\ServicePart::where('code', '08268-M99-Z0NZ1')->first();
        $partBusi = \App\Models\ServicePart::where('code', '31919-K25-601')->first();

        // Slot 09:00: 2 booked (1 remaining)
        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0001'],
            [
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '081234567890',
                'plate_number' => 'B 4123 KZA',
                'motorcycle_type' => 'Honda Vario 160',
                'service_date' => $today,
                'service_time' => '09:00',
                'service_package_id' => $pkgLengkap->id,
                'selected_parts' => [$partMpx2->id, $partGardan->id],
                'complaints' => 'Tarikan agak berat saat tanjakan',
                'estimated_total_price' => $pkgLengkap->price + $partMpx2->price + $partGardan->price,
                'status' => 'confirmed',
                'notes' => 'Pelanggan minta selesai sebelum jam 11',
            ]
        );

        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0002'],
            [
                'customer_name' => 'Siti Rahmawati',
                'customer_phone' => '082198765432',
                'plate_number' => 'B 3891 TGG',
                'motorcycle_type' => 'Honda Scoopy Prestige',
                'service_date' => $today,
                'service_time' => '09:00',
                'service_package_id' => $pkgRingan->id,
                'selected_parts' => [$partMpx2->id],
                'complaints' => 'Ganti oli rutin 2.000 KM',
                'estimated_total_price' => $pkgRingan->price + $partMpx2->price,
                'status' => 'in_progress',
                'notes' => 'Sedang dikerjakan di Pit 2',
            ]
        );

        // Slot 10:00: 3 booked (0 remaining -> FULL / PENUH!)
        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0003'],
            [
                'customer_name' => 'Ahmad Fauzi',
                'customer_phone' => '085712341234',
                'plate_number' => 'B 6542 XYZ',
                'motorcycle_type' => 'Honda Beat Deluxe',
                'service_date' => $today,
                'service_time' => '10:00',
                'service_package_id' => $pkgCvt->id,
                'selected_parts' => [$partMpx2->id, $partGardan->id],
                'complaints' => 'Ada bunyi gredek di putaran bawah',
                'estimated_total_price' => $pkgCvt->price + $partMpx2->price + $partGardan->price,
                'status' => 'confirmed',
                'notes' => 'Pit 1',
            ]
        );

        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0004'],
            [
                'customer_name' => 'Dimas Pratama',
                'customer_phone' => '081388776655',
                'plate_number' => 'B 5519 KOT',
                'motorcycle_type' => 'Honda PCX 160 ABS',
                'service_date' => $today,
                'service_time' => '10:00',
                'service_package_id' => $pkgLengkap->id,
                'selected_parts' => [$partBusi->id],
                'complaints' => 'Check up berkala 10.000 KM',
                'estimated_total_price' => $pkgLengkap->price + $partBusi->price,
                'status' => 'confirmed',
                'notes' => 'Pit 3',
            ]
        );

        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0005'],
            [
                'customer_name' => 'Dewi Anggraini',
                'customer_phone' => '087812345678',
                'plate_number' => 'B 3012 UYY',
                'motorcycle_type' => 'Honda Genio CBS',
                'service_date' => $today,
                'service_time' => '10:00',
                'service_package_id' => $pkgRingan->id,
                'selected_parts' => [$partMpx2->id],
                'complaints' => 'Servis berkala & ganti oli',
                'estimated_total_price' => $pkgRingan->price + $partMpx2->price,
                'status' => 'confirmed',
                'notes' => 'Pit 4',
            ]
        );

        // Slot 13:00: 1 booked
        \App\Models\Booking::updateOrCreate(
            ['booking_code' => 'AHASS-' . date('Ymd') . '-0006'],
            [
                'customer_name' => 'Rian Hidayat',
                'customer_phone' => '089611223344',
                'plate_number' => 'B 6999 ABC',
                'motorcycle_type' => 'Honda ADV 160',
                'service_date' => $today,
                'service_time' => '13:00',
                'service_package_id' => $pkgLengkap->id,
                'selected_parts' => [$partMpx2->id],
                'complaints' => 'Rem depan agak dalam',
                'estimated_total_price' => $pkgLengkap->price + $partMpx2->price,
                'status' => 'confirmed',
                'notes' => 'Booking lewat web',
            ]
        );
    }
}
