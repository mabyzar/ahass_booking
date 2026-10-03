<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_phone',
        'plate_number',
        'motorcycle_type',
        'service_date',
        'service_time',
        'service_package_id',
        'selected_parts',
        'complaints',
        'estimated_total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'date:Y-m-d',
        'selected_parts' => 'array',
        'estimated_total_price' => 'float',
    ];

    protected $appends = [
        'formatted_price',
        'parts_details',
    ];

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function getPartsDetailsAttribute()
    {
        if (empty($this->selected_parts) || !is_array($this->selected_parts)) {
            return [];
        }

        return ServicePart::whereIn('id', $this->selected_parts)->get();
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->estimated_total_price, 0, ',', '.');
    }

    public static function generateBookingCode(): string
    {
        $prefix = 'AHASS-' . date('Ymd') . '-';
        $latest = self::where('booking_code', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;
        if ($latest) {
            $lastCode = $latest->booking_code;
            $parts = explode('-', $lastCode);
            $lastNum = (int) end($parts);
            $number = $lastNum + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
