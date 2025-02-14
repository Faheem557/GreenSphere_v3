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
        'growth_habit',
        'soil_type',
        'temperature_range',
        'humidity_requirements',
        'fertilizer_needs',
        'blooming_season',
        'mature_height',
        'growth_rate',
        'pet_friendly',
        'maintenance_level',
        'propagation_method'
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
        'tropical' => 'Tropical Plants',
        'cacti' => 'Cacti',
        'aquatic' => 'Aquatic Plants',
        'medicinal' => 'Medicinal Plants'
    ];

    public const SUB_CATEGORIES = [
        'indoor' => [
            'foliage' => 'Foliage Plants',
            'hanging' => 'Hanging Plants',
            'climbing' => 'Climbing Plants',
            'palm' => 'Palm Plants'
        ],
        'outdoor' => [
            'shrubs' => 'Shrubs',
            'trees' => 'Trees',
            'climbers' => 'Climbing Plants',
            'groundcover' => 'Ground Cover Plants'
        ],
        'flowering' => [
            'annual' => 'Annual Flowers',
            'perennial' => 'Perennial Flowers',
            'bulbs' => 'Flowering Bulbs',
            'roses' => 'Roses'
        ]
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

    public const SOIL_TYPES = [
        'well_draining' => 'Well-Draining Potting Mix',
        'cactus_mix' => 'Cactus & Succulent Mix',
        'peat_based' => 'Peat-Based Mix',
        'loamy' => 'Loamy Soil',
        'sandy' => 'Sandy Soil'
    ];

    public const GROWTH_RATES = [
        'slow' => 'Slow Growing',
        'moderate' => 'Moderate Growth',
        'fast' => 'Fast Growing'
    ];

    public const MAINTENANCE_LEVELS = [
        'low' => 'Low Maintenance',
        'medium' => 'Medium Maintenance',
        'high' => 'High Maintenance'
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

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_plant');
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'plant_id', 'user_id')
            ->withTimestamps();
    }
} 