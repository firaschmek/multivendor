<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminPayoutController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorShopController;
use App\Http\Controllers\Vendor\VendorTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Vendor Registration
Route::get('/vendor/register', function () {
    return view('vendor.register');
})->name('vendor.register');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Reviews (dans la section Authenticated Routes)
    Route::middleware(['auth'])->group(function () {
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::post('/reviews/{review}/helpful', [ReviewController::class, 'markHelpful'])->name('reviews.helpful');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard - Redirects based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'vendor') {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('home');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/{product}', [AdminProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AdminProductController::class, 'bulkAction'])->name('bulk-action');
    });

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/update-payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('update-payment');
        Route::post('/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('invoice');
    });

    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [AdminCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Customers Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('index');
        Route::get('/{customer}', [AdminCustomerController::class, 'show'])->name('show');
        Route::delete('/{customer}', [AdminCustomerController::class, 'deactivate'])->name('deactivate');
        Route::post('/{customer}/reactivate', [AdminCustomerController::class, 'reactivate'])->name('reactivate');
    });

    // Payouts/Withdrawals Management
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', [AdminPayoutController::class, 'index'])->name('index');
        Route::get('/{withdrawal}', [AdminPayoutController::class, 'show'])->name('show');
        Route::post('/{withdrawal}/approve', [AdminPayoutController::class, 'approve'])->name('approve');
        Route::post('/{withdrawal}/reject', [AdminPayoutController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [AdminPayoutController::class, 'bulkApprove'])->name('bulk-approve');
    });

    // Vendor Management
    Route::resource('vendors', AdminVendorController::class);
    Route::post('vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('vendors.approve');
    Route::post('vendors/{vendor}/suspend', [AdminVendorController::class, 'suspend'])->name('vendors.suspend');
});

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [VendorProductController::class, 'index'])->name('index');
        Route::get('/create', [VendorProductController::class, 'create'])->name('create');
        Route::post('/', [VendorProductController::class, 'store'])->name('store');
        Route::get('/{product}', [VendorProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [VendorProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [VendorProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [VendorProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}/toggle-status', [VendorProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/images/{image}', [VendorProductController::class, 'deleteImage'])->name('delete-image');
    });

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [VendorOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [VendorOrderController::class, 'show'])->name('show');
        Route::post('/{order}/update-status', [VendorOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/update-tracking', [VendorOrderController::class, 'updateTracking'])->name('update-tracking');
        Route::get('/{order}/invoice', [VendorOrderController::class, 'printInvoice'])->name('invoice');
    });

    // Shop Settings
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/edit', [VendorShopController::class, 'edit'])->name('edit');
        Route::put('/update', [VendorShopController::class, 'update'])->name('update');
    });

    // Transactions & Earnings
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [VendorTransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [VendorTransactionController::class, 'show'])->name('show');
        Route::get('/withdrawal/request', [VendorTransactionController::class, 'withdrawalRequest'])->name('withdrawal.request');
        Route::post('/withdrawal/process', [VendorTransactionController::class, 'processWithdrawal'])->name('withdrawal.process');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
