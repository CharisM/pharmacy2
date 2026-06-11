<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about',   fn() => view('about'))->name('about');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::redirect('/shop', '/')->name('shop');
Route::get('/categories/{category?}', [ShopController::class, 'categories'])->name('categories');

// ── User Auth ─────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/password/forgot',       [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/forgot',      [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}',[AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/password/reset',       [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/email/verify',  [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
Route::post('/email/verify', [AuthController::class, 'verifyEmailCode'])->middleware('throttle:6,1')->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendEmailCode'])->middleware('throttle:6,1')->name('verification.send');

// ── Admin Auth ────────────────────────────────────────────────────────────────
Route::get('/adminlogin',  [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/adminlogin', [AuthController::class, 'adminLogin']);
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

// ── Authenticated User routes (web guard, non-admin only) ─────────────────────
Route::middleware(['auth:web', 'verified', 'not.admin'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/checkout-selected', [CartController::class, 'checkoutSelected'])->name('cart.checkout-selected');
    Route::get('/checkout', fn() => view('checkout'))->name('checkout');
    Route::post('/checkout',  [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('orders.confirmation');

    Route::get('/profile',      [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/my-orders',    [OrderController::class, 'myOrders'])->name('orders.my');

    Route::post('/contact', [MessageController::class, 'store'])->name('contact.send');

    Route::get('/messages',                  [MessageController::class, 'userIndex'])->name('messages.index');
    Route::get('/messages/{message}',        [MessageController::class, 'userThread'])->name('messages.thread');
    Route::post('/messages/{message}/reply', [MessageController::class, 'userReply'])->name('messages.reply');
});

// Cart mutations also need web+not.admin
Route::middleware(['auth:web', 'verified', 'not.admin'])->group(function () {
    Route::post('/cart/add/{product}',       [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/buynow/{product}',    [CartController::class, 'buyNow'])->name('cart.buynow');
    Route::patch('/cart/update/{cartItem}',  [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear',             [CartController::class, 'clear'])->name('cart.clear');
});

// ── Admin routes (admin guard only) ──────────────────────────────────────────
Route::middleware(['auth:admin', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/',      [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{user}',  [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::delete('/users',         [AdminController::class, 'deleteAllUsers'])->name('users.deleteAll');
        Route::post('/users/reset',     [AdminController::class, 'resetUsers'])->name('users.reset');
        Route::post('/users/clear-all', [AdminController::class, 'clearAllUsers'])->name('users.clearAll');

        // Products (Stock Inventory lives on dashboard)
        Route::get('/products',              fn() => redirect()->route('admin.dashboard'))->name('products');
        Route::post('/products',             [AdminController::class, 'storeProduct'])->name('products.store');
        Route::put('/products/{product}',    [AdminController::class, 'updateProduct'])->name('products.update');
        Route::patch('/products/{product}/stock', [AdminController::class, 'updateStock'])->name('products.stock');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

        // Orders
        Route::get('/orders',                  [AdminController::class, 'orders'])->name('orders');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

        // Messages
        Route::get('/messages',                  [MessageController::class, 'adminIndex'])->name('messages');
        Route::get('/messages/{message}',        [MessageController::class, 'adminThread'])->name('messages.thread');
        Route::post('/messages/{message}/reply', [MessageController::class, 'adminReply'])->name('messages.reply');
        Route::delete('/messages/{message}',     [MessageController::class, 'adminDestroy'])->name('messages.destroy');
    });
