<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantReview extends Model
{
    protected $fillable = [
        'plant_id',
        'user_id',
        'rating',
        'comment',
        'growth_progress',
        'care_difficulty',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'growth_progress' => 'json'
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