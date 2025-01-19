<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantCareGuide extends Model
{
    protected $fillable = [
        'plant_id',
        'watering_schedule',
        'light_requirements',
        'temperature_range',
        'humidity_level',
        'fertilizing_schedule',
        'pruning_tips',
        'common_problems',
        'seasonal_care'
    ];

    protected $casts = [
        'temperature_range' => 'json',
        'common_problems' => 'json',
        'seasonal_care' => 'json'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
} 