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
        'specifications',
        'care_level',
        'water_needs',
        'light_needs',
        'height',
        'pot_size',
        'maturity_time',
        'season',
        'toxicity',
        'delivery_options',
        'growth_habit'
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
        'vegetables' => 'Vegetables',
        'bonsai' => 'Bonsai Plants',
        'air_purifying' => 'Air Purifying Plants',
        'tropical' => 'Tropical Plants'
    ];

    public const CARE_LEVELS = [
        'easy' => 'Easy Care',
        'moderate' => 'Moderate Care',
        'expert' => 'Expert Care'
    ];

    public const WATER_NEEDS = [
        'low' => 'Low (Once every 2-3 weeks)',
        'medium' => 'Medium (Weekly)',
        'high' => 'High (2-3 times per week)'
    ];

    public const LIGHT_NEEDS = [
        'low' => 'Low Light',
        'medium' => 'Medium Light',
        'bright' => 'Bright Indirect Light',
        'direct' => 'Direct Sunlight'
    ];

    public const DELIVERY_OPTIONS = [
        'standard' => 'Standard Delivery (3-5 days)',
        'express' => 'Express Delivery (1-2 days)',
        'pickup' => 'Store Pickup'
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