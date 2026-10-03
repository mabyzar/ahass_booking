<?php

namespace App\Repositories\Eloquent;

use App\Models\ServicePart;
use App\Repositories\Contracts\ServicePartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServicePartRepository implements ServicePartRepositoryInterface
{
    public function getActiveParts(): Collection
    {
        return ServicePart::where('is_active', true)->orderBy('category', 'asc')->get();
    }

    public function getByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return new Collection();
        }
        return ServicePart::whereIn('id', $ids)->get();
    }

    public function calculateTotalPrice(array $ids): float
    {
        if (empty($ids)) {
            return 0.0;
        }
        return (float) ServicePart::whereIn('id', $ids)->sum('price');
    }
}
