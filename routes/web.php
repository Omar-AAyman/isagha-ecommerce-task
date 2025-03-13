<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect to login if not authenticated, otherwise go to dashboard
Route::get('/', function () {
    return Auth::check() ? redirect()->route('products.index') : view('auth.login');
});

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Social login routes
Route::prefix('auth')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirect'])->name('social.login');
    Route::get('/{provider}/callback', [SocialAuthController::class, 'callback']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::delete('/remove/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');
    });

    Route::prefix('payment')->group(function () {
        Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
        Route::get('/webhook', [PaymentController::class, 'handleWebhook']);
        Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');
    });
});
