<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantMaintenance extends Model
{
    protected $fillable = [
        'plant_id',
        'user_id',
        'watering_schedule',
        'fertilizing_schedule',
        'pruning_schedule',
        'repotting_schedule',
        'last_watered_at',
        'last_fertilized_at',
        'next_maintenance_date'
    ];

    protected $casts = [
        'watering_schedule' => 'json',
        'fertilizing_schedule' => 'json',
        'last_watered_at' => 'datetime',
        'last_fertilized_at' => 'datetime',
        'next_maintenance_date' => 'datetime'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 