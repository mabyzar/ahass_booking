<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ServicePackage;
use App\Models\ServicePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Standard AHASS operational service slots (excl. 12:00 lunch break)
     */
    public const OPERATIONAL_SLOTS = [
        '08:00',
        '09:00',
        '10:00',
        '11:00',
        '13:00',
        '14:00',
        '15:00',
        '16:00',
    ];

    public const MAX_PER_SLOT = 3;

    /**
     * Display the SPA view or return JSON list of bookings for AJAX.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getBookingsData($request);
        }

        $packages = ServicePackage::where('is_active', true)->orderBy('price', 'asc')->get();
        $parts = ServicePart::where('is_active', true)->orderBy('category', 'asc')->get();
        $today = Carbon::today()->toDateString();
        $operationalSlots = self::OPERATIONAL_SLOTS;

        return view('bookings.index', compact('packages', 'parts', 'today', 'operationalSlots'));
    }

    /**
     * AJAX endpoint to get filtered bookings list with stats.
     */
    public function getBookingsData(Request $request)
    {
        $query = Booking::with('servicePackage');

        // Filter: Tanggal Servis
        if ($request->filled('date')) {
            $query->whereDate('service_date', $request->date);
        }

        // Filter: Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter: Paket Servis
        if ($request->filled('package_id') && $request->package_id !== 'all') {
            $query->where('service_package_id', $request->package_id);
        }

        // Filter: Search (Plat Nomor, Nama Pelanggan, Tipe Motor, Booking Code)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('motorcycle_type', 'LIKE', "%{$search}%")
                  ->orWhere('booking_code', 'LIKE', "%{$search}%");
            });
        }

        // Order by date & time slot
        $bookings = $query->orderBy('service_date', 'desc')
                          ->orderBy('service_time', 'asc')
                          ->get();

        // Calculate quick stats
        $targetDate = $request->input('date', Carbon::today()->toDateString());
        $todayBookings = Booking::whereDate('service_date', $targetDate)->where('status', '!=', 'cancelled')->get();

        $stats = [
            'total_bookings' => $todayBookings->count(),
            'confirmed' => $todayBookings->where('status', 'confirmed')->count(),
            'in_progress' => $todayBookings->where('status', 'in_progress')->count(),
            'completed' => $todayBookings->where('status', 'completed')->count(),
            'total_revenue' => $todayBookings->where('status', 'completed')->sum('estimated_total_price'),
        ];

        return response()->json([
            'success' => true,
            'data' => $bookings,
            'stats' => $stats,
            'count' => $bookings->count(),
        ]);
    }

    /**
     * AJAX endpoint to check availability of all slots for a given date.
     */
    public function getSlots(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        // Group active bookings by time slot for that date
        $slotCounts = Booking::whereDate('service_date', $date)
            ->where('status', '!=', 'cancelled')
            ->select('service_time', DB::raw('count(*) as total'))
            ->groupBy('service_time')
            ->pluck('total', 'service_time')
            ->toArray();

        $slotsData = [];
        $isToday = $date === Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i');

        foreach (self::OPERATIONAL_SLOTS as $slot) {
            $bookedCount = $slotCounts[$slot] ?? 0;
            $remaining = max(0, self::MAX_PER_SLOT - $bookedCount);
            $isFull = $bookedCount >= self::MAX_PER_SLOT;

            // Optional past check for today
            $isPast = $isToday && ($slot < $currentTime);

            $slotsData[] = [
                'time' => $slot,
                'booked_count' => $bookedCount,
                'max_slots' => self::MAX_PER_SLOT,
                'remaining_slots' => $remaining,
                'is_full' => $isFull,
                'is_past' => $isPast,
                'is_available' => !$isFull && !$isPast,
                'badge_class' => $isFull ? 'danger' : ($remaining === 1 ? 'warning' : 'success'),
                'status_label' => $isFull ? 'PENUH (0 Slot)' : "Tersedia ({$remaining} Slot)",
            ];
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'slots' => $slotsData,
        ]);
    }

    /**
     * Store a newly created booking with strict backend slot validation.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|min:9|max:20',
            'plate_number' => ['required', 'string', 'max:15'],
            'motorcycle_type' => 'required|string|max:100',
            'service_date' => 'required|date|after_or_equal:today',
            'service_time' => 'required|string|in:' . implode(',', self::OPERATIONAL_SLOTS),
            'service_package_id' => 'required|exists:service_packages,id',
            'selected_parts' => 'nullable|array',
            'selected_parts.*' => 'exists:service_parts,id',
            'complaints' => 'nullable|string|max:500',
        ], [
            'customer_name.required' => 'Nama lengkap pelanggan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'plate_number.required' => 'Nomor Plat motor wajib diisi (contoh: B 1234 ABC).',
            'motorcycle_type.required' => 'Tipe motor Honda wajib dipilih atau diisi.',
            'service_date.required' => 'Tanggal servis wajib ditentukan.',
            'service_date.after_or_equal' => 'Tanggal servis tidak boleh tanggal lampau.',
            'service_time.required' => 'Jam servis wajib dipilih.',
            'service_time.in' => 'Jam servis tidak sesuai dengan jadwal operasional AHASS.',
            'service_package_id.required' => 'Silakan pilih paket servis yang diinginkan.',
            'service_package_id.exists' => 'Paket servis tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan pada input formulir.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $serviceDate = $request->service_date;
        $serviceTime = $request->service_time;

        // Clean & format plate number (uppercase, standardized spacing)
        $cleanPlate = strtoupper(trim(preg_replace('/\s+/', ' ', $request->plate_number)));

        try {
            $booking = DB::transaction(function () use ($request, $serviceDate, $serviceTime, $cleanPlate) {
                // 2. VALIDASI BACKEND: Cek kuota slot dengan lockForUpdate untuk mencegah race conditions
                $currentSlotCount = Booking::whereDate('service_date', $serviceDate)
                    ->where('service_time', $serviceTime)
                    ->where('status', '!=', 'cancelled')
                    ->lockForUpdate()
                    ->count();

                if ($currentSlotCount >= self::MAX_PER_SLOT) {
                    throw new \DomainException(
                        "Maaf, slot jam {$serviceTime} pada tanggal {$serviceDate} sudah PENUH (maksimal " . self::MAX_PER_SLOT . " kendaraan). Silakan pilih slot jam lain atau tanggal berbeda."
                    );
                }

                // Cek duplikasi plat nomor pada jam yang sama
                $duplicateVehicle = Booking::whereDate('service_date', $serviceDate)
                    ->where('service_time', $serviceTime)
                    ->where('plate_number', $cleanPlate)
                    ->where('status', '!=', 'cancelled')
                    ->first();

                if ($duplicateVehicle) {
                    throw new \DomainException(
                        "Kendaraan dengan plat {$cleanPlate} sudah terdaftar pada slot jam {$serviceTime} tanggal {$serviceDate}."
                    );
                }

                // Hitung total estimasi harga
                $package = ServicePackage::findOrFail($request->service_package_id);
                $totalPrice = (float) $package->price;

                $selectedParts = $request->input('selected_parts', []);
                if (!empty($selectedParts) && is_array($selectedParts)) {
                    $partsPrice = ServicePart::whereIn('id', $selectedParts)->sum('price');
                    $totalPrice += (float) $partsPrice;
                }

                // Buat Booking Baru
                $booking = Booking::create([
                    'booking_code' => Booking::generateBookingCode(),
                    'customer_name' => trim($request->customer_name),
                    'customer_phone' => trim($request->customer_phone),
                    'plate_number' => $cleanPlate,
                    'motorcycle_type' => trim($request->motorcycle_type),
                    'service_date' => $serviceDate,
                    'service_time' => $serviceTime,
                    'service_package_id' => $package->id,
                    'selected_parts' => !empty($selectedParts) ? array_values(array_map('intval', $selectedParts)) : null,
                    'complaints' => $request->complaints ? trim($request->complaints) : null,
                    'estimated_total_price' => $totalPrice,
                    'status' => 'confirmed',
                    'notes' => 'Pendaftaran melalui Web Booking AHASS',
                ]);

                return $booking->load('servicePackage');
            });

            return response()->json([
                'success' => true,
                'message' => "Booking servis untuk {$cleanPlate} berhasil didaftarkan pada slot jam {$serviceTime}!",
                'data' => $booking,
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [
                    'service_time' => [$e->getMessage()]
                ],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show booking details.
     */
    public function show($id)
    {
        $booking = Booking::with('servicePackage')->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking,
            'parts' => $booking->parts_details,
        ]);
    }

    /**
     * Update booking status (e.g. In Progress, Completed, Cancelled).
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $booking->status = $request->status;
        if ($request->has('notes')) {
            $booking->notes = $request->notes;
        }
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => "Status booking {$booking->booking_code} berhasil diperbarui menjadi: " . strtoupper($booking->status),
            'data' => $booking->load('servicePackage'),
        ]);
    }

    /**
     * Delete or cancel booking.
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak ditemukan.',
            ], 404);
        }

        $code = $booking->booking_code;
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => "Data booking {$code} berhasil dihapus dari sistem.",
        ]);
    }
}
