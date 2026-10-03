<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePart extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];
}
