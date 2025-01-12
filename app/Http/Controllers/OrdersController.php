<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
// ... other imports

class OrdersController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'location.address' => 'required|string',
                'location.city' => 'required|string',
                'location.state' => 'required|string',
                'location.zip' => 'required|string',
                'payment_method' => 'required|in:cod'
            ]);

            // Log the order attempt
            Log::info('Order attempt', [
                'user_id' => auth()->id(),
                'data' => $validated
            ]);

            // Process the order
            // ... your order processing logic ...

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'redirect' => route('orders.confirmation', ['order' => $order->id])
            ]);

        } catch (\Exception $e) {
            Log::error('Order processing failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process order. Please try again.'
            ], 500);
        }
    }
} 