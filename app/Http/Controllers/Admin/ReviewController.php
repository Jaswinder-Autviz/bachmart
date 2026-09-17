<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ShopService $shopService) {}

    public function index(Request $request)
    {
        $query = Review::with(['shop', 'user', 'product'])->latest();

        if ($request->status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->status === 'approved') {
            $query->where('is_approved', true);
        }

        $reviews = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'counts'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        $this->shopService->updateRating($review->shop);
        return back()->with('success', 'Review approved.');
    }

    public function destroy(Review $review)
    {
        $shop = $review->shop;
        $review->delete();
        $this->shopService->updateRating($shop);
        return back()->with('success', 'Review deleted.');
    }
}
