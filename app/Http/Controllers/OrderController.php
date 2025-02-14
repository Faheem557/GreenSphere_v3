<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\OrderStatusChanged;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Events\NewOrderReceived;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Validation\Rule;
use App\Models\Cart;

class OrderController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update order status', ['only' => ['updateStatus']]);
        $this->middleware('permission:create orders', ['only' => ['store']]);
    }

    /**
     * Calculate the total price of items in cart
     *
     * @param array $cart
     * @return float
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        return $total;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'location' => 'required|array',
                'location.address' => 'required|string',
                'location.city' => 'required|string',
                'location.state' => 'required|string',
                'location.zip' => 'required|string',
                'payment_method' => 'required|in:cod,online',
                'delivery_option_id' => ['required', 'string', Rule::in(array_keys(Plant::DELIVERY_OPTIONS))],
                'preferred_delivery_date' => 'required|date|after:today',
                'delivery_instructions' => 'nullable|string|max:500'
            ]);

            // Add better error logging
            Log::info('Order validation passed', [
                'user_id' => auth()->id(),
                'delivery_option' => $request->delivery_option_id,
                'available_options' => array_keys(Plant::DELIVERY_OPTIONS)
            ]);

            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 400);
            }

            // Get the first plant's seller_id (assuming all items are from same seller)
            $firstItemId = array_key_first($cart);
            $plant = Plant::findOrFail($firstItemId);
            $sellerId = $plant->user_id; // or seller_id depending on your column name

            DB::beginTransaction();

            // Create order with seller_id and buyer_id
            $order = Order::create([
                'seller_id' => $sellerId,
                'buyer_id' => auth()->id(), // Changed from user_id to buyer_id
                'total_amount' => $this->calculateTotal($cart),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                // 'shipping_address' => $request->shipping_address,
                'shipping_address' => json_encode([
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->location['address'],
                    'city' => $request->location['city'],
                    'state' => $request->location['state'],
                    'zip' => $request->location['zip']
                ])
            ]);

            // Create order items
            foreach ($cart as $id => $details) {
                $plant = Plant::findOrFail($id);

                // Verify seller matches
                if ($plant->user_id !== $sellerId) {
                    throw new \Exception('Cannot process order with items from different sellers');
                }

                $order->items()->create([
                    'plant_id' => $id,
                    'quantity' => $details['quantity'],
                    'unit_price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity']
                ]);

                // Update plant quantity
                $plant->decrement('quantity', $details['quantity']);
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'buyer_id' => auth()->id(),
                'seller_id' => $sellerId
            ]);

            $order->update([
                'delivery_option_id' => $request->delivery_option_id,
                'preferred_delivery_date' => $request->preferred_delivery_date,
                'delivery_instructions' => $request->delivery_instructions
            ]);

            return response()->json([
                'success' => true,
                'order' => $order->load('items'),
                'redirect' => route('orders.confirmation', $order->id)
            ]);
            // Log the order details
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'buyer_id' => auth()->id(),
                'seller_id' => $sellerId,
                'total_amount' => $order->total_amount,
                'delivery_option' => $request->delivery_option_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Creation Error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing your order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        event(new OrderStatusChanged($order));

        return response()->json(['success' => true]);
    }

    public function userOrders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with(['items.plant', 'seller'])
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

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
        if (auth()->id() !== $order->buyer_id && auth()->id() !== $order->seller_id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.plant', 'buyer', 'seller']);
        return view('orders.show', compact('order'));
    }

    public function confirmation(Order $order)
    {
        // Ensure user can only view their own order confirmation
        if (auth()->id() !== $order->buyer_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.confirmation', compact('order'));
    }

    public function status(Order $order)
    {
        // Ensure the user can only view their own orders
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.status', compact('order'));
    }

    public function track(Order $order)
    {
        // Ensure the user can only track their own orders
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.plant', 'buyer', 'seller']);
        return view('user.order-tracking', compact('order'));
    }

    public function checkout(CheckoutRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $cart = Cart::getItems();
            
            if (empty($cart)) {
                throw new \Exception('Cart is empty');
            }
            
            DB::beginTransaction();
            
            try {
                $order = Order::create([
                    'buyer_id' => auth()->id(),
                    'seller_id' => 1, // Assuming this is correct for your case
                    'shipping_address' => json_encode([
                        'name' => $request->name,
                        'email' => $request->email,
                        'address' => $request->location['address'],
                        'city' => $request->location['city'],
                        'state' => $request->location['state'],
                        'zip' => $request->location['zip']
                    ]),
                    'phone' => $validatedData['phone'],
                    'payment_method' => $validatedData['payment_method'],
                    'delivery_date' => $validatedData['delivery']['date'],
                    'delivery_slot' => $validatedData['delivery']['slot'],
                    'delivery_instructions' => $validatedData['delivery']['instructions'],
                    'status' => 'pending',
                    'total_amount' => Cart::getTotal()
                ]);

                // Create order items
                foreach ($cart as $id => $item) {
                    $plant = Plant::findOrFail($id);
                    
                    // Check stock availability
                    if ($plant->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for plant: {$plant->name}");
                    }
                    
                    $order->items()->create([
                        'plant_id' => $id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity']
                    ]);
                    
                    // Update plant stock
                    $plant->decrement('quantity', $item['quantity']);
                }
                
                // Clear the cart after successful order
                Cart::clear();
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    // redirect to user dashboard
                    'redirect' => route('user.dashboard')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Checkout error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request_data' => $validatedData ?? null
            ]);
            
            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function active()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(10);
        
        return view('user.orders.active', compact('orders'));
    }

    public function index()
    {
        $orders = auth()->user()->orders()
            ->whereNotIn('status', ['pending'])
            ->with(['plants', 'seller'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
