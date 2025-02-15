<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerOrderController;

// Public routes
Route::get('/', function () {
    // If user is authenticated, redirect to their appropriate dashboard
    if (Auth::check()) {
        if (Auth::user()->hasRole('seller')) {
            return redirect()->route('seller.dashboard');
        } elseif (auth()->user()->hasRole('user')) {
            return redirect()->route('user.dashboard');
        }
    }
    // If user is a guest, show welcome page
    return view('welcome');
})->name('home');

// Authentication routes are handled by Breeze/auth.php
require __DIR__ . '/auth.php';

// Role-specific routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Common Profile Routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::put('/profile', 'updateProfile')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::get('/profile/preferences', 'preferences')->name('profile.preferences');
        Route::post('/profile/preferences', 'updatePreferences');
        Route::get('/profile/location', 'location')->name('profile.location');
        Route::post('/profile/location', 'updateLocation');
    });

    // Plant Routes (Public)
    Route::controller(PlantController::class)->group(function () {
        Route::get('/plants', 'index')->name('plants.index');
        Route::get('/plants/{plant}', 'show')->name('plants.show');
    });

    // User Routes
    Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [PlantController::class, 'userDashboard'])->name('dashboard');

        // Cart Routes
        Route::controller(CartController::class)->group(function () {
            Route::get('/cart', 'index')->name('cart.index');
            Route::post('/cart/add/{plant}', 'add')->name('cart.add');
            Route::patch('/cart/update', 'update')->name('cart.update');
            Route::delete('/cart/remove', 'remove')->name('cart.remove');
            Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
        });

        // Order Routes
        Route::controller(OrderController::class)->group(function () {
            Route::post('/orders', 'store')->name('orders.store');
            Route::get('/orders', 'userOrders')->name('orders.index');
            Route::get('/orders/{order}', 'show')->name('orders.show');
            Route::get('/orders/{order}/confirmation', 'confirmation')->name('orders.confirmation');
            Route::get('/orders/{order}/track', 'track')->name('orders.track');
        });

        // Review Routes
        Route::controller(ReviewController::class)->group(function () {
            Route::post('/plants/{plant}/reviews', 'store')->name('reviews.store');
            Route::get('/my-reviews', 'userReviews')->name('user.reviews');
        });
    });

    // Seller Routes
    Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
        // Seller Dashboard
        Route::get('/dashboard', [PlantController::class, 'getSellerStats'])
            ->name('dashboard');

        // Plant Management
        Route::controller(PlantController::class)->group(function () {
            Route::get('/inventory', 'inventory')->name('inventory');
            Route::get('/plants/add', 'create')->name('plants.add');
            Route::post('/plants', 'store')->name('plants.store');
            Route::get('/plants/{plant}/edit', 'edit')->name('plants.edit');
            Route::put('/plants/{plant}', 'update')->name('plants.update');
            Route::delete('/plants/{plant}', 'destroy')->name('plants.destroy');
            Route::post('/plants/{plant}/stock', 'updateStock')->name('plants.update-stock');
            Route::post('/plants/{plant}/toggle-status', 'toggleStatus')->name('plants.toggle-status');
        });

        // Order Management
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'sellerOrders')->name('orders.index');
            Route::get('/orders/{order}', 'show')->name('orders.show');
            Route::post('/orders/{order}/status', 'updateStatus')->name('orders.updateStatus');
        });

        // Review Management
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/reviews', 'sellerReviews')->name('reviews.index');
            Route::post('/reviews/{review}/reply', 'reply')->name('reviews.reply');
        });
    });

    // Review Routes
    Route::controller(ReviewController::class)->group(function () {
        Route::post('/plants/{plant}/reviews', 'store')->name('reviews.store');
    });
});

// Plant Catalog Routes
Route::get('/catalog', [PlantController::class, 'catalog'])->name('plants.catalog');
Route::get('/plants/{plant}', [PlantController::class, 'show'])->name('plants.show');

// Cart Routes (for users only)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{plant}', 'add')->name('cart.add');
        Route::patch('/cart/update', 'update')->name('cart.update');
        Route::delete('/cart/remove', 'remove')->name('cart.remove');
        Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
    });
});

// Order Routes (for authenticated users)
Route::middleware(['auth', 'role:user'])->group(function () {
    // Cart Routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{plant}', 'add')->name('cart.add');
        Route::patch('/cart/update', 'update')->name('cart.update');
        Route::delete('/cart/remove', 'remove')->name('cart.remove');
        Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
    });

    // Order Routes
    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders', 'store')->name('orders.store');
        Route::get('/orders', 'userOrders')->name('orders.index');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::get('/orders/{order}/confirmation', 'confirmation')->name('orders.confirmation');
    });
});

// Seller Order Routes
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::controller(SellerOrderController::class)->prefix('seller')->name('seller.')->group(function () {
        Route::get('/orders', 'sellerOrders')->name('orders.index');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::put('/orders/{order}/status', 'updateStatus')->name('orders.update-status');
    });
});

Route::get('/orders/{order}/status', [OrderController::class, 'status'])->name('orders.status');

// User routes
Route::middleware(['auth'])->group(function () {
    // ... existing routes ...
    Route::get('/orders/{order}/track', 'OrderController@track')->name('orders.track');
    Route::get('/my-plants', [PlantController::class, 'myPlants'])->name('plants.my-plants');
});

Route::get('/test-storage', function() {
    $disk = Storage::disk('public');
    return [
        'storage_path' => storage_path('app/public'),
        'public_path' => public_path('storage'),
        'files' => $disk->files('plants'),
        'exists' => $disk->exists('plants'),
        'storage_link_exists' => file_exists(public_path('storage')),
    ];
});

Route::get('/debug-image/{filename}', function($filename) {
    $path = storage_path('app/public/plants/' . $filename);
    return [
        'file_exists' => file_exists($path),
        'storage_path' => $path,
        'public_url' => asset('storage/plants/' . $filename),
        'permissions' => decoct(fileperms($path) & 0777),
        'storage_link' => file_exists(public_path('storage')),
    ];
});

Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
