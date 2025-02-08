<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Order;
use App\Models\UserProfile;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'preferences',
        'location'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'json',
            'location' => 'json'
        ];
    }

    protected $appends = ['unread_pending_orders_count'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function purchases()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function getUnreadPendingOrdersCountAttribute()
    {
        if ($this->hasRole('seller')) {
            return $this->orders()
                ->where('status', 'pending')
                ->where('is_read', false)
                ->count();
        }
        return 0;
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'location' => 'json',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($user) {
            $user->profile()->create([
                'gardening_level' => 'beginner',
                'notification_preferences' => [
                    'email' => true,
                    'push' => true,
                    'sms' => false
                ]
            ]);
        });
    }
}
