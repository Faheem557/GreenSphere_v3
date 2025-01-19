<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'sub_category',
        'price',
        'quantity',
        'description',
        'care_instructions',
        'is_active',
        'user_id',
        'image',
        'delivery_info',
        'specifications'
    ];

    protected $casts = [
        'care_instructions' => 'json',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'specifications' => 'json',
        'delivery_info' => 'json'
    ];

    public const CATEGORIES = [
        'indoor' => 'Indoor Plants',
        'outdoor' => 'Outdoor Plants',
        'flowering' => 'Flowering Plants',
        'succulents' => 'Succulents',
        'herbs' => 'Herbs',
        'vegetables' => 'Vegetables'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function careGuide()
    {
        return $this->hasOne(PlantCareGuide::class);
    }

    public function maintenance()
    {
        return $this->hasMany(PlantMaintenance::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
} 