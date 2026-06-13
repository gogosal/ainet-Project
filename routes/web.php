<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\View3dController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyImagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Employee;
use App\Http\Controllers\Admin;

// Home landing page
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Serve private tshirt images
Route::get('/private-image/{filename}', [MyImagesController::class, 'servePrivateImage'])->name('private-image');

// Fallback
Route::fallback(fn() => redirect('/'));

// Public routes
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
Route::get('/email/verify', fn() => view('auth.verify-email'))->middleware('auth')->name('verification.notice');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/view3d', [View3dController::class, 'index'])->name('view3d');

// Cart — accessible without auth
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart', [CatalogController::class, 'addToCart'])->name('cart.store');
Route::post('/view3d/cart', [View3dController::class, 'addToCart'])->name('view3d.cart');
Route::patch('/cart/{index}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{index}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Save cart on login
Route::post('/logout', function (\Illuminate\Http\Request $request) {

    $cart = $request->session()->get('cart');

    auth()->guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($cart) {
        $request->session()->put('cart', $cart);
    }

    return redirect('/');
})->name('logout');

require __DIR__ . '/settings.php';

// Authenticated + verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-images', [MyImagesController::class, 'index'])->name('my-images');
    Route::post('/my-images', [MyImagesController::class, 'store'])->name('my-images.store');
    Route::put('/my-images/{image}', [MyImagesController::class, 'update'])->name('my-images.update');
    Route::delete('/my-images/{image}', [MyImagesController::class, 'destroy'])->name('my-images.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
});

// Client only
Route::middleware(['auth', 'verified', 'user.type:C'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Employee
Route::middleware(['auth', 'user.type:F'])->group(function () {
    Route::get('/employee/orders', [Employee\OrderController::class, 'index'])->name('employee.orders');
    Route::get('/employee/orders/{order}', [Employee\OrderController::class, 'show'])->name('employee.orders.show');
    Route::post('/employee/orders/{order}/close', [Employee\OrderController::class, 'close'])->name('employee.orders.close');
});

// Admin + Client
Route::middleware(['auth', 'user.type:A,C'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// Admin
Route::middleware(['auth', 'user.type:A'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/catalog', [Admin\CatalogController::class, 'index'])->name('catalog');
    Route::post('/catalog', [Admin\CatalogController::class, 'store'])->name('catalog.store');
    Route::post('/catalog/{image}', [Admin\CatalogController::class, 'update'])->name('catalog.update');
    Route::delete('/catalog/{image}', [Admin\CatalogController::class, 'destroy'])->name('catalog.destroy');

    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/colors', [Admin\ColorController::class, 'index'])->name('colors');
    Route::post('/colors', [Admin\ColorController::class, 'store'])->name('colors.store');
    Route::post('/colors/{color}', [Admin\ColorController::class, 'update'])->name('colors.update');
    Route::delete('/colors/{color}', [Admin\ColorController::class, 'destroy'])->name('colors.destroy');

    Route::get('/prices', [Admin\PriceController::class, 'edit'])->name('prices');
    Route::post('/prices', [Admin\PriceController::class, 'update'])->name('prices.update');

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/close', [Admin\OrderController::class, 'close'])->name('orders.close');
    Route::post('/orders/{order}/cancel', [Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/receipt', [Admin\OrderController::class, 'receipt'])->name('orders.receipt');

    Route::get('/customers', [Admin\CustomerController::class, 'index'])->name('customers');
    Route::post('/customers/{user}/toggle-block', [Admin\CustomerController::class, 'toggleBlock'])->name('customers.toggle-block');
    Route::delete('/customers/{user}', [Admin\CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/staff', [Admin\StaffController::class, 'index'])->name('staff');
    Route::post('/staff', [Admin\StaffController::class, 'store'])->name('staff.store');
    Route::post('/staff/{user}', [Admin\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{user}', [Admin\StaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('/statistics', [Admin\StatisticsController::class, 'index'])->name('statistics');
});
