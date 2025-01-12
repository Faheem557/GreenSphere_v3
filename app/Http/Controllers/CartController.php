<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use App\Events\CartUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CartController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);
        
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Plant $plant)
    {
        try {
            \Log::info('Add to cart request received', [
                'plant_id' => $plant->id,
                'quantity' => $request->quantity
            ]);

            // Validate request
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            // Check stock availability
            if ($plant->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available'
                ], 422);
            }

            // Get current cart
            $cart = session()->get('cart', []);
            
            // Check if plant already exists in cart
            if (isset($cart[$plant->id])) {
                // Update quantity if total doesn't exceed available stock
                $newQuantity = $cart[$plant->id]['quantity'] + $request->quantity;
                if ($newQuantity > $plant->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items than available in stock'
                    ], 400);
                }
                $cart[$plant->id]['quantity'] = $newQuantity;
            } else {
                // Add new item to cart
                $cart[$plant->id] = [
                    'name' => $plant->name,
                    'quantity' => $request->quantity,
                    'price' => $plant->price,
                    'image' => $plant->image,
                    'seller_id' => $plant->user_id
                ];
            }
            
            // Update session
            session()->put('cart', $cart);
            
            // Broadcast the event
            try {
                broadcast(new CartUpdated(auth()->user()))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Pusher broadcast failed: ' . $e->getMessage());
            }
            
            \Log::info('Item added to cart successfully', [
                'cart_count' => count($cart)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $plant->name . ' added to cart successfully',
                'cart_count' => count($cart)
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart Add Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:plants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            broadcast(new CartUpdated(auth()->user()))->toOthers();
        }
        
        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            
            broadcast(new CartUpdated(auth()->user()))->toOthers();
        }
        
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if(empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $user = auth()->user();
        $total = $this->calculateTotal($cart);
        
        return view('cart.checkout', compact('cart', 'total', 'user'));
    }

    private function calculateTotal($cart)
    {
        return collect($cart)->sum(function($item) {
            return $item['quantity'] * $item['price'];
        });
    }
} 