<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NewOrderReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        // Broadcast to seller-specific channel
        return new Channel('seller-' . $this->order->seller_id);
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'buyer_name' => $this->order->buyer->name,
            'total_amount' => $this->order->total_amount,
        ];
    }
} 