<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

Route::get('/', function () {
    return view('welcome');
});




Route::get('auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);
