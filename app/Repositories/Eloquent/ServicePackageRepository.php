<?php

namespace App\Repositories\Eloquent;

use App\Models\ServicePackage;
use App\Repositories\Contracts\ServicePackageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServicePackageRepository implements ServicePackageRepositoryInterface
{
    public function getActivePackages(): Collection
    {
        return ServicePackage::where('is_active', true)->orderBy('price', 'asc')->get();
    }

    public function findById(int $id): ?ServicePackage
    {
        return ServicePackage::find($id);
    }
}
