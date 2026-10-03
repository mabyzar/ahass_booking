<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    /**
     * Get filtered bookings.
     */
    public function getAllFiltered(array $filters = []): Collection;

    /**
     * Find booking by ID.
     */
    public function findById(int $id): ?Booking;

    /**
     * Find booking with relations loaded.
     */
    public function findWithRelations(int $id, array $relations = ['servicePackage']): ?Booking;

    /**
     * Create a new booking record.
     */
    public function create(array $data): Booking;

    /**
     * Update an existing booking.
     */
    public function update(int $id, array $data): Booking;

    /**
     * Delete booking by ID.
     */
    public function delete(int $id): bool;

    /**
     * Count active bookings for a specific date and time slot (with optional lock for update).
     */
    public function countBookingsBySlot(string $date, string $time, bool $lockForUpdate = false): int;

    /**
     * Find existing active booking by plate number on the same slot.
     */
    public function findByPlateAndSlot(string $date, string $time, string $plateNumber): ?Booking;

    /**
     * Get count of bookings per slot for a given date.
     */
    public function getSlotCountsByDate(string $date): array;

    /**
     * Get statistics for a given date.
     */
    public function getStatsByDate(string $date): array;
}
