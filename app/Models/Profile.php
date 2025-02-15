<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'gardening_level',
        'plant_preferences',
        'location_data',
        'notification_preferences'
    ];

    protected $casts = [
        'plant_preferences' => 'array',
        'location_data' => 'array',
        'notification_preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 