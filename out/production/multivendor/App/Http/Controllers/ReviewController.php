<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to leave a review.');
        }

        // Check if user has already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        // Validate
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        // Check if user has purchased this product
        $hasPurchased = \App\Models\Order::where('user_id', Auth::id())
            ->whereHas('orderItems', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('status', 'delivered')
            ->exists();

        // Create review
        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'verified_purchase' => $hasPurchased,
        ]);

        // Update product rating
        $this->updateProductRating($product);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        // Check ownership
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update product rating
        $this->updateProductRating($review->product);

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Check ownership
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $product = $review->product;
        $review->delete();

        // Update product rating
        $this->updateProductRating($product);

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful(Review $review)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login'], 401);
        }

        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count,
        ]);
    }

    /**
     * Update product average rating
     */
    private function updateProductRating(Product $product)
    {
        $averageRating = $product->reviews()->avg('rating');
        $reviewsCount = $product->reviews()->count();

        $product->update([
            'average_rating' => round($averageRating, 2),
            'reviews_count' => $reviewsCount,
        ]);
    }
}
