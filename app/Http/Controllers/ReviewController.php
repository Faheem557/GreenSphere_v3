<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ReviewController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create reviews', ['only' => ['store']]);
        $this->middleware('permission:manage reviews', ['only' => ['update', 'destroy']]);
    }

    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
            'images.*' => 'nullable|image|max:2048'
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'plant_id' => $plant->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $review->images()->create(['path' => $path]);
            }

        }

        return back()->with('success', 'Review added successfully!');
    }

    public function userReviews()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with(['plant', 'plant.seller'])
            ->latest()
            ->paginate(10);
        
        return view('user.reviews', compact('reviews'));
    }

    public function sellerReviews()
    {
        $reviews = Review::whereHas('plant', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['user', 'plant'])
            ->latest()
            ->paginate(10);
        
        return view('seller.reviews', compact('reviews'));
    }

    public function reply(Review $review, Request $request)
    {
        $request->validate([
            'reply' => 'required|string|max:500'
        ]);

        // Check if the review belongs to the seller's plant
        if ($review->plant->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review->update([
            'seller_reply' => $request->reply,
            'replied_at' => now()
        ]);

        return back()->with('success', 'Reply added successfully');
    }
} 