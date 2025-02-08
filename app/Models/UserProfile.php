<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gardening_level',
        'notification_preferences'
    ];

    protected $casts = [
        'notification_preferences' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}