<?php

namespace App\Repositories\Contracts;

use App\Models\ServicePart;
use Illuminate\Database\Eloquent\Collection;

interface ServicePartRepositoryInterface
{
    /**
     * Get all active spare parts.
     */
    public function getActiveParts(): Collection;

    /**
     * Get parts by IDs.
     */
    public function getByIds(array $ids): Collection;

    /**
     * Calculate total price of selected parts.
     */
    public function calculateTotalPrice(array $ids): float;
}
