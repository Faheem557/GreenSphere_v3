<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;

class SellerOrderController extends Controller
{
    public function sellerOrders()
    {
        $orders = Order::where('seller_id', auth()->id())
            ->with(['items.plant', 'buyer'])
            ->latest()
            ->paginate(10);

        return view('seller.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if (auth()->id() !== $order->seller_id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['buyer']);
        return view('seller.order-details', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|required_if:status,shipped|string|max:255',
        ]);

        $order->update([
            'status' => $validated['status'],
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
        ]);

        // Send notification to user about order status update
        $order->buyer->notify(new OrderStatusUpdated($order));

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}
