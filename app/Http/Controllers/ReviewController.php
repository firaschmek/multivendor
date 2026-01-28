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
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول لترك تقييم');
        }

        // Check if user has already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'لقد قمت بتقييم هذا المنتج مسبقاً');
        }

        // Validate
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'يرجى اختيار التقييم',
            'rating.min' => 'التقييم يجب أن يكون على الأقل نجمة واحدة',
            'rating.max' => 'التقييم يجب أن لا يتجاوز 5 نجوم',
            'review.required' => 'يرجى كتابة تقييمك',
            'review.min' => 'التقييم يجب أن يحتوي على 10 أحرف على الأقل',
            'review.max' => 'التقييم يجب أن لا يتجاوز 1000 حرف',
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

        return redirect()->back()->with('success', 'شكراً لك على تقييمك!');
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, Review $review)
    {
        // Check ownership
        if ($review->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا التقييم');
        }

        // Validate
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'يرجى اختيار التقييم',
            'rating.min' => 'التقييم يجب أن يكون على الأقل نجمة واحدة',
            'rating.max' => 'التقييم يجب أن لا يتجاوز 5 نجوم',
            'review.required' => 'يرجى كتابة تقييمك',
            'review.min' => 'التقييم يجب أن يحتوي على 10 أحرف على الأقل',
            'review.max' => 'التقييم يجب أن لا يتجاوز 1000 حرف',
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update product rating
        $this->updateProductRating($review->product);

        return redirect()->back()->with('success', 'تم تحديث تقييمك بنجاح');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Check ownership
        if ($review->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا التقييم');
        }

        $product = $review->product;
        $review->delete();

        // Update product rating
        $this->updateProductRating($product);

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful(Review $review)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'يرجى تسجيل الدخول'], 401);
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
