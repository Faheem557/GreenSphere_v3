<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'buyer_id',
        'seller_id',
        'shipping_address',
        'phone',
        'payment_method',
        'delivery_option_id',
        'delivery_date',
        'delivery_slot',
        'delivery_instructions'
    ];

    protected $casts = [
        'shipping_address' => 'string',
        'delivery_date' => 'datetime',
        'total' => 'decimal:2'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plants()
    {
        return $this->belongsToMany(Plant::class, 'order_plant');
    }

    // Helper method to calculate total
    public function calculateTotal(): void
    {
        $this->total = $this->items->sum('subtotal');
        $this->save();
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
