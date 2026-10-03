<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
