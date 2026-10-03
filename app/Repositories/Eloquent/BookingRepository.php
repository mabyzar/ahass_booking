<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    /**
     * Get filtered bookings.
     */
    public function getAllFiltered(array $filters = []): Collection
    {
        $query = Booking::with('servicePackage');

        if (!empty($filters['date'])) {
            $query->whereDate('service_date', $filters['date']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['package_id']) && $filters['package_id'] !== 'all') {
            $query->where('service_package_id', $filters['package_id']);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('motorcycle_type', 'LIKE', "%{$search}%")
                  ->orWhere('booking_code', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('service_date', 'desc')
                     ->orderBy('service_time', 'asc')
                     ->get();
    }

    /**
     * Find booking by ID.
     */
    public function findById(int $id): ?Booking
    {
        return Booking::find($id);
    }

    /**
     * Find booking with relations loaded.
     */
    public function findWithRelations(int $id, array $relations = ['servicePackage']): ?Booking
    {
        return Booking::with($relations)->find($id);
    }

    /**
     * Create a new booking.
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Update an existing booking.
     */
    public function update(int $id, array $data): Booking
    {
        $booking = Booking::findOrFail($id);
        $booking->update($data);
        return $booking;
    }

    /**
     * Delete booking by ID.
     */
    public function delete(int $id): bool
    {
        $booking = Booking::findOrFail($id);
        return (bool) $booking->delete();
    }

    /**
     * Count active bookings for a specific date and time slot.
     */
    public function countBookingsBySlot(string $date, string $time, bool $lockForUpdate = false): int
    {
        $query = Booking::whereDate('service_date', $date)
            ->where('service_time', $time)
            ->where('status', '!=', 'cancelled');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->count();
    }

    /**
     * Find active booking with the same plate number on the slot.
     */
    public function findByPlateAndSlot(string $date, string $time, string $plateNumber): ?Booking
    {
        return Booking::whereDate('service_date', $date)
            ->where('service_time', $time)
            ->where('plate_number', $plateNumber)
            ->where('status', '!=', 'cancelled')
            ->first();
    }

    /**
     * Get booking counts grouped by slot time for a given date.
     */
    public function getSlotCountsByDate(string $date): array
    {
        return Booking::whereDate('service_date', $date)
            ->where('status', '!=', 'cancelled')
            ->select('service_time', DB::raw('count(*) as total'))
            ->groupBy('service_time')
            ->pluck('total', 'service_time')
            ->toArray();
    }

    /**
     * Get statistics summary for a given date.
     */
    public function getStatsByDate(string $date): array
    {
        $activeBookings = Booking::whereDate('service_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        return [
            'total_bookings' => $activeBookings->count(),
            'confirmed' => $activeBookings->where('status', 'confirmed')->count(),
            'in_progress' => $activeBookings->where('status', 'in_progress')->count(),
            'completed' => $activeBookings->where('status', 'completed')->count(),
            'total_revenue' => $activeBookings->where('status', 'completed')->sum('estimated_total_price'),
        ];
    }
}
