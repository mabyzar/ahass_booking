<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\ServicePackageRepositoryInterface;
use App\Repositories\Contracts\ServicePartRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
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

    protected BookingRepositoryInterface $bookingRepo;
    protected ServicePackageRepositoryInterface $packageRepo;
    protected ServicePartRepositoryInterface $partRepo;

    public function __construct(
        BookingRepositoryInterface $bookingRepo,
        ServicePackageRepositoryInterface $packageRepo,
        ServicePartRepositoryInterface $partRepo
    ) {
        $this->bookingRepo = $bookingRepo;
        $this->packageRepo = $packageRepo;
        $this->partRepo = $partRepo;
    }

    /**
     * Get operational slots list.
     */
    public function getOperationalSlots(): array
    {
        return self::OPERATIONAL_SLOTS;
    }

    /**
     * Retrieve slot availability for a specified date.
     */
    public function getAvailableSlots(string $date): array
    {
        $slotCounts = $this->bookingRepo->getSlotCountsByDate($date);

        $slotsData = [];
        $isToday = $date === Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i');

        foreach (self::OPERATIONAL_SLOTS as $slot) {
            $bookedCount = $slotCounts[$slot] ?? 0;
            $remaining = max(0, self::MAX_PER_SLOT - $bookedCount);
            $isFull = $bookedCount >= self::MAX_PER_SLOT;
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

        return $slotsData;
    }

    /**
     * Get filtered bookings and date summary stats.
     */
    public function getBookingsList(array $filters): array
    {
        $bookings = $this->bookingRepo->getAllFiltered($filters);
        $targetDate = $filters['date'] ?? Carbon::today()->toDateString();
        $stats = $this->bookingRepo->getStatsByDate($targetDate);

        return [
            'bookings' => $bookings,
            'stats' => $stats,
            'count' => $bookings->count(),
        ];
    }

    /**
     * Create a new booking with business rules and slot capacity checks.
     *
     * @throws \DomainException
     */
    public function createBooking(array $data): Booking
    {
        $serviceDate = $data['service_date'];
        $serviceTime = $data['service_time'];
        $cleanPlate = strtoupper(trim(preg_replace('/\s+/', ' ', $data['plate_number'])));

        return DB::transaction(function () use ($data, $serviceDate, $serviceTime, $cleanPlate) {
            // 1. BUSINESS RULE: Slot capacity limit check with pessimistic lock
            $currentSlotCount = $this->bookingRepo->countBookingsBySlot($serviceDate, $serviceTime, true);

            if ($currentSlotCount >= self::MAX_PER_SLOT) {
                throw new \DomainException(
                    "Maaf, slot jam {$serviceTime} pada tanggal {$serviceDate} sudah PENUH (maksimal " . self::MAX_PER_SLOT . " kendaraan). Silakan pilih slot jam lain atau tanggal berbeda."
                );
            }

            // 2. BUSINESS RULE: Duplicate vehicle plate check on same slot
            $duplicateVehicle = $this->bookingRepo->findByPlateAndSlot($serviceDate, $serviceTime, $cleanPlate);
            if ($duplicateVehicle) {
                throw new \DomainException(
                    "Kendaraan dengan plat {$cleanPlate} sudah terdaftar pada slot jam {$serviceTime} tanggal {$serviceDate}."
                );
            }

            // 3. BUSINESS RULE: Validate & retrieve package
            $package = $this->packageRepo->findById($data['service_package_id']);
            if (!$package) {
                throw new \DomainException("Paket servis Honda yang dipilih tidak ditemukan.");
            }

            // 4. BUSINESS RULE: Calculate total pricing (package + selected parts)
            $totalPrice = (float) $package->price;
            $selectedParts = $data['selected_parts'] ?? [];

            if (!empty($selectedParts) && is_array($selectedParts)) {
                $partsPrice = $this->partRepo->calculateTotalPrice($selectedParts);
                $totalPrice += $partsPrice;
            }

            // 5. Create Booking via Repository
            $booking = $this->bookingRepo->create([
                'booking_code' => Booking::generateBookingCode(),
                'customer_name' => trim($data['customer_name']),
                'customer_phone' => trim($data['customer_phone']),
                'plate_number' => $cleanPlate,
                'motorcycle_type' => trim($data['motorcycle_type']),
                'service_date' => $serviceDate,
                'service_time' => $serviceTime,
                'service_package_id' => $package->id,
                'selected_parts' => !empty($selectedParts) ? array_values(array_map('intval', $selectedParts)) : null,
                'complaints' => !empty($data['complaints']) ? trim($data['complaints']) : null,
                'estimated_total_price' => $totalPrice,
                'status' => 'confirmed',
                'notes' => 'Pendaftaran melalui Web Booking AHASS',
            ]);

            return $booking->load('servicePackage');
        });
    }

    /**
     * Get booking details with parts.
     */
    public function getBookingDetails(int $id): ?Booking
    {
        return $this->bookingRepo->findWithRelations($id, ['servicePackage']);
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(int $id, string $status, ?string $notes = null): Booking
    {
        $updateData = ['status' => $status];
        if ($notes !== null) {
            $updateData['notes'] = $notes;
        }

        return $this->bookingRepo->update($id, $updateData);
    }

    /**
     * Delete booking.
     */
    public function deleteBooking(int $id): bool
    {
        return $this->bookingRepo->delete($id);
    }
}
