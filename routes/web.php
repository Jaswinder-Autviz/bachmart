<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\SearchController;
use App\Http\Controllers\Customer\LeadController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\ShopController as SellerShop;
use App\Http\Controllers\Seller\ProductController as SellerProduct;
use App\Http\Controllers\Seller\AnalyticsController as SellerAnalytics;
use App\Http\Controllers\Seller\FeaturedController as SellerFeatured;
use App\Http\Controllers\Seller\PaymentController as SellerPayment;
use App\Http\Controllers\Seller\NotificationController as SellerNotification;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ShopController as AdminShop;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\FeaturedController as AdminFeatured;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscription;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\LeadController as AdminLead;
use App\Http\Controllers\Admin\ReviewController as AdminReview;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/deals', [HomeController::class, 'deals'])->name('deals');
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/set-city', [HomeController::class, 'setCity'])->name('set-city');

// Category
Route::get('/category/{category:slug}', [HomeController::class, 'category'])->name('category.show');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Products
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Shops
Route::get('/shop/{shop:slug}', [ShopController::class, 'show'])->name('shop.show');

// Lead tracking (AJAX)
Route::post('/lead/track', [LeadController::class, 'track'])->name('lead.track');

// Reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/register/seller', [AuthController::class, 'showSellerRegister'])->name('register.seller');
    Route::post('/register/seller', [AuthController::class, 'registerSeller'])->name('register.seller.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
*/

Route::prefix('seller')->name('seller.')->middleware(['auth', 'role:seller'])->group(function () {

    // Shop setup (before seller.active check)
    Route::get('/shop/create', [SellerShop::class, 'create'])->name('shop.create');
    Route::post('/shop', [SellerShop::class, 'store'])->name('shop.store');

    // All other seller routes require active shop
    Route::middleware('seller.active')->group(function () {
        Route::get('/dashboard', [SellerDashboard::class, 'index'])->name('dashboard');

        // Shop management
        Route::get('/shop/edit', [SellerShop::class, 'edit'])->name('shop.edit');
        Route::put('/shop', [SellerShop::class, 'update'])->name('shop.update');

        // Pay-Per-Product Listing Payment Flow (Must precede Route::resource to avoid {product} wildcard collision)
        Route::get('/products/payment', [SellerPayment::class, 'showListingPayment'])->name('products.payment');
        Route::post('/products/payment/process', [SellerPayment::class, 'processListingPayment'])->name('products.payment.process');
        Route::get('/payments', [SellerPayment::class, 'history'])->name('payments.index');

        // Products Resource & Management
        Route::resource('products', SellerProduct::class);
        Route::post('/products/{product}/toggle-status', [SellerProduct::class, 'toggleStatus'])->name('products.toggle-status');
        Route::delete('/products/{product}/images/{image}', [SellerProduct::class, 'destroyImage'])->name('products.images.destroy');
        Route::post('/products/{product}/set-primary/{image}', [SellerProduct::class, 'setPrimaryImage'])->name('products.images.primary');

        // Analytics
        Route::get('/analytics', [SellerAnalytics::class, 'index'])->name('analytics');
        Route::get('/analytics/data', [SellerAnalytics::class, 'data'])->name('analytics.data');

        // Featured
        Route::get('/products/{product}/feature', [SellerFeatured::class, 'show'])->name('featured.show');
        Route::post('/products/{product}/feature', [SellerFeatured::class, 'store'])->name('featured.store');

        // Legacy Subscription Redirect (Safeguard)
        Route::get('/subscription', fn () => redirect()->route('seller.products.payment'))->name('subscription.index');

        // Leads
        Route::get('/leads', [SellerDashboard::class, 'leads'])->name('leads');

        // Notifications
        Route::get('/notifications', [SellerNotification::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [SellerNotification::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [SellerNotification::class, 'markAllRead'])->name('notifications.read-all');

        // Settings
        Route::get('/settings', [SellerDashboard::class, 'settings'])->name('settings');
        Route::put('/settings', [SellerDashboard::class, 'updateSettings'])->name('settings.update');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Sellers / Users
    Route::get('/sellers', [AdminUser::class, 'sellers'])->name('sellers.index');
    Route::get('/sellers/{user}', [AdminUser::class, 'show'])->name('sellers.show');
    Route::post('/sellers/{user}/approve', [AdminUser::class, 'approve'])->name('sellers.approve');
    Route::post('/sellers/{user}/block', [AdminUser::class, 'block'])->name('sellers.block');
    Route::post('/sellers/{user}/unblock', [AdminUser::class, 'unblock'])->name('sellers.unblock');
    Route::delete('/sellers/{user}', [AdminUser::class, 'destroy'])->name('sellers.destroy');

    // Customers
    Route::get('/customers', [AdminUser::class, 'customers'])->name('customers.index');

    // Shops
    Route::get('/shops', [AdminShop::class, 'index'])->name('shops.index');
    Route::get('/shops/{shop}', [AdminShop::class, 'show'])->name('shops.show');
    Route::post('/shops/{shop}/activate', [AdminShop::class, 'activate'])->name('shops.activate');
    Route::post('/shops/{shop}/deactivate', [AdminShop::class, 'deactivate'])->name('shops.deactivate');
    Route::post('/shops/{shop}/verify', [AdminShop::class, 'verify'])->name('shops.verify');
    Route::post('/shops/{shop}/feature', [AdminShop::class, 'feature'])->name('shops.feature');

    // Products
    Route::get('/products', [AdminProduct::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [AdminProduct::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProduct::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProduct::class, 'update'])->name('products.update');
    Route::post('/products/{product}/approve', [AdminProduct::class, 'approve'])->name('products.approve');
    Route::post('/products/{product}/reject', [AdminProduct::class, 'reject'])->name('products.reject');
    Route::post('/products/{product}/feature', [AdminProduct::class, 'feature'])->name('products.feature');
    Route::post('/products/{product}/unfeature', [AdminProduct::class, 'unfeature'])->name('products.unfeature');
    Route::post('/products/{product}/sold-out', [AdminProduct::class, 'markSoldOut'])->name('products.sold-out');
    Route::post('/products/{product}/deactivate', [AdminProduct::class, 'deactivate'])->name('products.deactivate');
    Route::delete('/products/{product}', [AdminProduct::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::resource('categories', AdminCategory::class);
    Route::post('/categories/{category}/toggle-status', [AdminCategory::class, 'toggleStatus'])->name('categories.toggle-status');

    // Featured Listings
    Route::get('/featured', [AdminFeatured::class, 'index'])->name('featured.index');
    Route::post('/featured/{featured}/approve', [AdminFeatured::class, 'approve'])->name('featured.approve');
    Route::post('/featured/{featured}/cancel', [AdminFeatured::class, 'cancel'])->name('featured.cancel');

    // Subscriptions
    Route::get('/subscriptions', [AdminSubscription::class, 'index'])->name('subscriptions.index');
    Route::resource('subscription-plans', AdminSubscription::class, ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']])->names([
        'index' => 'subscription-plans.index',
        'create' => 'subscription-plans.create',
        'store' => 'subscription-plans.store',
        'edit' => 'subscription-plans.edit',
        'update' => 'subscription-plans.update',
        'destroy' => 'subscription-plans.destroy',
    ]);
    Route::post('/subscriptions/{subscription}/activate', [AdminSubscription::class, 'activate'])->name('subscriptions.activate');

    // Payments
    Route::get('/payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [AdminPayment::class, 'approve'])->name('payments.approve');

    // Leads / Reports
    Route::get('/leads', [AdminLead::class, 'index'])->name('leads.index');

    // Reviews
    Route::get('/reviews', [AdminReview::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReview::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [AdminReview::class, 'destroy'])->name('reviews.destroy');

    // Settings
    Route::get('/settings', [AdminSetting::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSetting::class, 'update'])->name('settings.update');
});
