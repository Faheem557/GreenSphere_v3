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
            
            // Update plant quantity in database (only decrement once)
            $plant->decrement('quantity', $request->quantity);
            
            // Update session
            session()->put('cart', $cart);
            
            // Get total items in cart
            $cartCount = array_sum(array_column($cart, 'quantity'));
            
            // Calculate remaining quantity correctly
            $remaining_quantity = $plant->quantity;
            
            return response()->json([
                'success' => true,
                'message' => $plant->name . ' added to cart successfully',
                'cart_count' => $cartCount,
                'remaining_quantity' => $remaining_quantity
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
        try {
            $request->validate([
                'id' => 'required|exists:plants,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = session()->get('cart', []);
            
            if(isset($cart[$request->id])) {
                $plant = Plant::findOrFail($request->id);
                $oldQuantity = $cart[$request->id]['quantity'];
                $newQuantity = $request->quantity;
                
                // Calculate quantity difference
                $quantityDifference = $oldQuantity - $newQuantity;
                
                // Update plant quantity in database
                if ($quantityDifference > 0) {
                    // If reducing cart quantity, restore to plant
                    $plant->increment('quantity', $quantityDifference);
                } else {
                    // If increasing cart quantity, check if enough stock
                    $neededQuantity = abs($quantityDifference);
                    if ($plant->quantity < $neededQuantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Not enough stock available'
                        ], 422);
                    }
                    $plant->decrement('quantity', $neededQuantity);
                }
                
                // Update cart quantity
                $cart[$request->id]['quantity'] = $newQuantity;
                session()->put('cart', $cart);
                
                // Calculate total items in cart
                $cartCount = array_sum(array_column($cart, 'quantity'));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'cart_count' => $cartCount,
                    'remaining_quantity' => $plant->quantity
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart'
            ], 500);
        }
    }

    public function remove(Request $request)
    {
        try {
            $cart = session()->get('cart', []);
            
            // Check if item exists in cart
            if(isset($cart[$request->id])) {
                // Get the plant and quantity being removed
                $plant = Plant::findOrFail($request->id);
                $quantityToRestore = $cart[$request->id]['quantity'];
                
                // Restore the quantity to the plant
                $plant->increment('quantity', $quantityToRestore);
                
                // Remove from cart
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                
                // Calculate total items in cart
                $cartCount = array_sum(array_column($cart, 'quantity'));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully',
                    'cart_count' => $cartCount,
                    'restored_quantity' => $quantityToRestore
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart Remove Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
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