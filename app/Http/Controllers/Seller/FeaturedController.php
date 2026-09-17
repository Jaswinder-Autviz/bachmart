<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class FeaturedController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function show(Product $product)
    {
        $this->authorize('feature', $product);
        $packages = PaymentService::getFeaturedPackages();
        $activeFeatured = $product->featuredRecord;
        return view('seller.featured.show', compact('product', 'packages', 'activeFeatured'));
    }

    public function store(Request $request, Product $product)
    {
        $this->authorize('feature', $product);

        $packages = PaymentService::getFeaturedPackages();
        $package = $request->validate(['package' => ['required', 'in:3days,7days,15days']])['package'];

        $amount = $packages[$package]['price'];

        $featured = FeaturedProduct::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'package' => $package,
            'amount_paid' => $amount,
            'status' => 'pending',
        ]);

        $payment = $this->paymentService->createFeaturedPayment(auth()->user(), $featured, $amount);

        // For MVP: auto-approve (in production, this would be after payment gateway callback)
        $this->paymentService->processManualApproval($payment);

        return redirect()->route('seller.products.index')
            ->with('success', "Product featured for {$packages[$package]['label']}! It will appear highlighted in listings.");
    }
}
