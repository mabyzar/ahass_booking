<?php

namespace App\Repositories\Contracts;

use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Collection;

interface ServicePackageRepositoryInterface
{
    /**
     * Get all active service packages.
     */
    public function getActivePackages(): Collection;

    /**
     * Find service package by ID.
     */
    public function findById(int $id): ?ServicePackage;
}
