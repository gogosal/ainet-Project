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

// Serve private tshirt images (custom uploads)
Route::get('/private-image/{filename}', function (string $filename) {
    $filename = basename($filename); // prevent path traversal
    foreach (['tshirt_images_private', 'tshirt_images'] as $dir) {
        $path = storage_path('app/private/' . $dir . '/' . $filename);
        if (file_exists($path)) {
            $mime = str_ends_with($filename, '.jpg') || str_ends_with($filename, '.jpeg') ? 'image/jpeg' : 'image/png';
            return response()->file($path, ['Content-Type' => $mime]);
        }
    }
    abort(404);
})->name('private-image');

// Fallback route for undefined paths
Route::fallback(function () {
    return redirect('/');
});

// Public routes
Route::get('/catalog', CatalogPage::class)->name('catalog');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/try-on', VirtualTryOnPage::class)->name('try-on');

require __DIR__ . '/settings.php';

// ==========================================
// Rotas Partilhadas (Acessíveis por C, F e A)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-images', MyImagesPage::class)->name('my-images');
    Route::get('/profile', ProfilePage::class)->name('profile');

    // Movido para aqui: Todos acedem
    Route::get('/orders', OrdersPage::class)->name('orders.index');
    Route::get('/orders/{order}', OrderDetailPage::class)->name('orders.show');

    // Receipt access adaptado para não dar erro aos Admins/Funcionários
    Route::get('/orders/{order}/receipt', function (\App\Models\Order $order) {
        $user = auth()->user();

        // Se for cliente (C), só pode ver se a encomenda for dele
        if ($user->type === 'C') {
            if (!$user->customer || $order->customer_id !== $user->customer->id) {
                abort(403);
            }
        }

        if (!$order->receipt_url) abort(404);
        $path = storage_path('app/private/' . $order->receipt_url);
        if (!file_exists($path)) abort(404);

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    })->name('orders.receipt');
});

// ==========================================
// Rotas Exclusivas de Cliente (C)
// ==========================================
Route::middleware(['auth', 'verified', 'user.type:C'])->group(function () {
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    // (As rotas das encomendas saíram daqui e foram para cima)
});

// ==========================================
// Rotas Exclusivas de Funcionário (F)
// ==========================================
Route::middleware(['auth', 'user.type:F'])->group(function () {
    Route::get('/employee/orders', EmployeeOrdersPage::class)->name('employee.orders');
});

// ==========================================
// Rotas Exclusivas de Admin (A)
// ==========================================
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
