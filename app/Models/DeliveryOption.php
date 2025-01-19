<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'estimated_days',
        'is_available',
        'coverage_area'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'coverage_area' => 'json'
    ];
} 