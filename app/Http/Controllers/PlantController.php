<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PlantRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PlantController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:add plants|edit plants|delete plants|manage inventory', [
            'only' => ['create', 'store', 'edit', 'update', 'destroy', 'inventory', 'updateStock']
        ]);
        $this->middleware('permission:manage cart', ['only' => ['addToCart']]);
    }

    public function index(Request $request)
    {
        $query = Plant::with('seller')->where('is_active', true);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $plants = $query->paginate(12);
        return view('plants.index', compact('plants'));
    }

    public function show(Plant $plant)
    {
        try {
            $plant->load(['seller', 'reviews.user']);
            return view('plants.show', compact('plant'));
        } catch (\Exception $e) {
            Log::error('Error loading plant details: ' . $e->getMessage());
            return back()->with('error', 'Error loading plant details. Please try again.');
        }
    }

    private function authorizeUser(Plant $plant)
    {
        if ($plant->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(PlantRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['is_active'] = true;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('plants', $filename, 'public');
                $data['image'] = $path;
            }

            // Log the delivery options being set
            Log::info('Creating plant with delivery options:', [
                'user_id' => Auth::id(),
                'delivery_options' => $data['delivery_options']
            ]);

            Plant::create($data);

            return redirect()
                ->route('seller.inventory')
                ->with('success', 'Plant added successfully!');

        } catch (\Exception $e) {
            Log::error('Plant creation error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $request->except(['image']),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error adding plant. Please try again.');
        }
    }

    public function getSellerStats()
    {
        $stats = [
            'total_plants' => Plant::where('user_id', auth()->id())->count(),
            'active_plants' => Plant::where('user_id', auth()->id())
                ->where('is_active', true)
                ->count(),
            'out_of_stock' => Plant::where('user_id', auth()->id())
                ->where('quantity', 0)
                ->count(),
            'total_orders' => Order::where('seller_id', auth()->id())->count(),
            'pending_orders' => Order::where('seller_id', auth()->id())
                ->where('status', 'pending')
                ->count(),
            'recent_orders' => Order::where('seller_id', auth()->id())
                ->with('buyer')
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('seller.dashboard', compact('stats'));
    }

    public function inventory()
    {
        $plants = Plant::where('user_id', Auth::user()->id)->get();
        return view('seller.inventory', compact('plants'));
    }

    public function updateStock(Plant $plant, Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $plant->update(['quantity' => $request->quantity]);
            
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Stock update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock'
            ], 500);
        }
    }

    public function toggleStatus(Plant $plant, Request $request)
    {
        $this->authorizeUser($plant);

        try {
            $plant->update([
                'is_active' => !$plant->is_active
            ]);

            return response()->json([
                'success' => true,
                'is_active' => $plant->is_active,
                'message' => $plant->is_active ? 'Plant activated successfully' : 'Plant deactivated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Plant status toggle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error updating plant status'
            ], 500);
        }
    }

    public function destroy(Plant $plant)
    {
        if ($plant->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($plant->image) {
            Storage::disk('public')->delete($plant->image);
        }
        
        $plant->delete();
        return response()->json(['success' => true]);
    }

    public function edit(Plant $plant)
    {
        if ($plant->user_id !== Auth::user()->id) {
            return redirect()
                ->route('seller.inventory')
                ->with('error', 'Unauthorized access');
        }
        
        return view('seller.editPlant', compact('plant'));
    }

    public function update(PlantRequest $request, Plant $plant)
    {
        if ($plant->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($plant->image) {
                    Storage::disk('public')->delete($plant->image);
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('plants', $filename, 'public');
                $data['image'] = $path;
            }

            // Log the delivery options being updated
            Log::info('Updating plant delivery options:', [
                'plant_id' => $plant->id,
                'user_id' => Auth::id(),
                'old_delivery_options' => $plant->delivery_options,
                'new_delivery_options' => $data['delivery_options']
            ]);

            $plant->update($data);

            return redirect()
                ->route('seller.inventory')
                ->with('success', 'Plant updated successfully!');

        } catch (\Exception $e) {
            Log::error('Plant update error', [
                'error' => $e->getMessage(),
                'plant_id' => $plant->id,
                'user_id' => Auth::id(),
                'data' => $request->except(['image']),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error updating plant. Please try again.');
        }
    }

    public function create()
    {
        return view('seller.addPlants');
    }

    public function addToCart(Plant $plant, Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:'.$plant->quantity
            ]);

            // Check if plant is still available
            if ($plant->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity not available'
                ], 422);
            }

            $cart = session()->get('cart', []);
            
            if (isset($cart[$plant->id])) {
                // Check if new total quantity exceeds available stock
                $newQuantity = $cart[$plant->id]['quantity'] + $request->quantity;
                if ($newQuantity > $plant->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items than available in stock'
                    ], 422);
                }
                $cart[$plant->id]['quantity'] = $newQuantity;
            } else {
                $cart[$plant->id] = [
                    'name' => $plant->name,
                    'quantity' => $request->quantity,
                    'price' => $plant->price,
                    'image' => $plant->image,
                    'seller_id' => $plant->user_id
                ];
            }
            
            session()->put('cart', $cart);

            // Update plant quantity
            $plant->decrement('quantity', $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Plant added to cart successfully!',
                'cart_count' => count($cart)
            ]);

        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart. Please try again.'
            ], 500);
        }
    }

    public function catalog(Request $request)
    {
        $query = Plant::where('is_active', true)
            ->where('quantity', '>', 0);

        // Category filter
        if ($request->has('categories')) {
            $query->whereIn('category', $request->categories);
        }

        // Care level filter
        if ($request->care_level) {
            $query->where('care_level', $request->care_level);
        }

        // Price range filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Delivery options filter
        if ($request->has('delivery_options')) {
            $query->whereJsonContains('delivery_options', $request->delivery_options);
        }

        // Water needs filter
        if ($request->water_needs) {
            $query->where('water_needs', $request->water_needs);
        }

        // Light needs filter
        if ($request->light_needs) {
            $query->where('light_needs', $request->light_needs);
        }

        // Search by name or description
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $plants = $query->with(['reviews', 'seller', 'careGuide'])
                        ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
                        ->paginate(12);

        return view('plants.catalog', compact('plants'));
    }

    public function userDashboard()
    {
        $stats = [
            'total_plants' => Plant::where('is_active', true)->count(),
            'available_plants' => Plant::where('is_active', true)
                // ->where('quantity', '>', 0)
                ->count(),
            'categories' => Plant::select('category')
                ->distinct()
                ->count(),
            'latest_plants' => Plant::where('is_active', true)
                // ->where('quantity', '>', 0)
                ->with('seller')
                ->latest()
                ->take(8)
                ->get(),
            'cart_count' => session()->get('cart', []) ? count(session()->get('cart')) : 0
        ];

        return view('user.home', compact('stats'));
    }
} 