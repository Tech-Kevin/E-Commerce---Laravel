<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:100',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user has purchased this product
        $hasPurchased = Order::whereHas('items', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->where('user_id', Auth::id())->exists();

        $review = Review::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $productId],
            [
                ...$validated,
                'verified_purchase' => $hasPurchased,
            ]
        );

        return response()->json(['message' => 'Review submitted successfully', 'review' => $review]);
    }

    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
