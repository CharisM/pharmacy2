<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;

Route::post('/cart/add/{product}', [CartController::class, 'add'])
    ->middleware('auth')
    ->name('cart.add');
// Public Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/adminlogin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/adminlogin', [AuthController::class, 'adminLogin']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PUBLIC PAGES
// Keep the named `shop` route but redirect it to home so the shop page is effectively removed
Route::redirect('/shop', '/')->name('shop');

Route::get('/categories/{category?}', [ShopController::class, 'categories'])->name('categories');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// PROTECTED ROUTES
Route::middleware('auth')->group(function () {

    // Example protected actions
    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    });
