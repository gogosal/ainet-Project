<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Catalog\CatalogPage;
use App\Livewire\Cart\CartPage;
use App\Livewire\Checkout\CheckoutPage;
use App\Livewire\Orders\OrdersPage;
use App\Livewire\Orders\OrderDetailPage;
use App\Livewire\Profile\ProfilePage;
use App\Livewire\MyImages\MyImagesPage;
use App\Livewire\Employee\EmployeeOrdersPage;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\AdminCatalogPage;
use App\Livewire\Admin\AdminCategoriesPage;
use App\Livewire\Admin\AdminColorsPage;
use App\Livewire\Admin\AdminPricesPage;
use App\Livewire\Admin\AdminOrdersPage;
use App\Livewire\Admin\AdminOrderDetailPage;
use App\Livewire\Admin\AdminCustomersPage;
use App\Livewire\Admin\AdminStaffPage;
use App\Livewire\Admin\AdminStatisticsPage;
use App\Livewire\VirtualTryOn\VirtualTryOnPage;

// Home landing page
Route::get('/', fn() => view('landing'))->name('home');

// Public routes
Route::get('/catalog', CatalogPage::class)->name('catalog');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/try-on', VirtualTryOnPage::class)->name('try-on');

// Private image serving (for customer's own images)
Route::get('/private-image/{path}', function (string $path) {
    $fullPath = storage_path('app/private/tshirt_images_private/' . basename($path));
    if (!file_exists($fullPath)) abort(404);
    // Allow: owner, employees, admins
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isEmployee() || $user->isAdmin()) {
            return response()->file($fullPath);
        }
        if ($user->isClient() && $user->customer) {
            // Check if the image belongs to this customer
            $image = \App\Models\TshirtImage::where('image_url', 'tshirt_images_private/' . basename($path))
                ->where('customer_id', $user->customer->id)
                ->first();
            if ($image) return response()->file($fullPath);
        }
    }
    abort(403);
})->name('private-image')->where('path', '.*');

require __DIR__.'/settings.php';

// Client routes
Route::middleware(['auth', 'verified', 'user.type:C'])->group(function () {
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/orders', OrdersPage::class)->name('orders.index');
    Route::get('/orders/{order}', OrderDetailPage::class)->name('orders.show');
    Route::get('/orders/{order}/receipt', function (\App\Models\Order $order) {
        $user = auth()->user();
        if ($order->customer_id !== $user->customer->id) abort(403);
        if (!$order->receipt_url) abort(404);
        $path = storage_path('app/private/' . $order->receipt_url);
        if (!file_exists($path)) abort(404);
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    })->name('orders.receipt');
    Route::get('/my-images', MyImagesPage::class)->name('my-images');
    Route::get('/profile', ProfilePage::class)->name('profile');
});

// Employee routes
Route::middleware(['auth', 'user.type:F'])->group(function () {
    Route::get('/employee/orders', EmployeeOrdersPage::class)->name('employee.orders');
});

// Admin routes
Route::middleware(['auth', 'user.type:A'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/catalog', AdminCatalogPage::class)->name('catalog');
    Route::get('/categories', AdminCategoriesPage::class)->name('categories');
    Route::get('/colors', AdminColorsPage::class)->name('colors');
    Route::get('/prices', AdminPricesPage::class)->name('prices');
    Route::get('/orders', AdminOrdersPage::class)->name('orders');
    Route::get('/orders/{order}', AdminOrderDetailPage::class)->name('orders.show');
    Route::get('/customers', AdminCustomersPage::class)->name('customers');
    Route::get('/staff', AdminStaffPage::class)->name('staff');
    Route::get('/statistics', AdminStatisticsPage::class)->name('statistics');

    // Admin receipt access
    Route::get('/orders/{order}/receipt', function (\App\Models\Order $order) {
        if (!$order->receipt_url) abort(404);
        $path = storage_path('app/private/' . $order->receipt_url);
        if (!file_exists($path)) abort(404);
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    })->name('orders.receipt');
});
