<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Status Updated')
            ->line('Your order #' . $this->order->id . ' has been updated.')
            ->line('Current Status: ' . ucfirst($this->order->status))
            ->when($this->order->tracking_number, function ($message) {
                return $message->line('Tracking Number: ' . $this->order->tracking_number);
            })
            ->action('View Order', route('orders.track', $this->order->id));
    }
}
