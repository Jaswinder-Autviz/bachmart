<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Services\ShopService;

class ReviewController extends Controller
{
    public function __construct(private ShopService $shopService) {}

    public function store(StoreReviewRequest $request)
    {
        $existing = Review::where('shop_id', $request->shop_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this shop.');
        }

        $review = Review::create([
            'shop_id' => $request->shop_id,
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // needs admin approval
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval.');
    }
}
