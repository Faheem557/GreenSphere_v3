<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $count;

    public function __construct($user)
    {
        $this->user = $user;
        $this->count = count(session()->get('cart', []));
    }

    public function broadcastOn()
    {
        try {
            return new Channel('cart-channel');
        } catch (\Exception $e) {
            \Log::error('Broadcasting error: ' . $e->getMessage());
            return null;
        }
    }

    public function broadcastWith()
    {
        return [
            'count' => $this->count
        ];
    }
} 