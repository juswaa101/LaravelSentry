<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('landing.page');

Route::get('/logout', [AuthenticationController::class, 'logout'])
    ->name('logout');

// Guest routes
Route::group(['middleware' => 'guest'], function () {
    // Authentication routes
    Route::get('/login', [AuthenticationController::class, 'login'])
        ->name('login');

    Route::get('/register', [AuthenticationController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthenticationController::class, 'loginProcess'])
        ->name('login.process')
        ->middleware('throttle:login-attempt');

    Route::post('/register', [AuthenticationController::class, 'registerProcess'])
        ->name('register.process');


    // Verify account routes
    Route::get('/verify/{token}', [VerifyAccountController::class, 'verifyAccountForm'])
        ->name('verify.form')
        ->middleware('signed');

    Route::post('/verify/{token}/send', [VerifyAccountController::class, 'verifyAccount'])
        ->name('verify');


    // Forgot password routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password/submit', [ForgotPasswordController::class, 'forgotPassword'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetPasswordForm'])
        ->name('password.reset.form')
        ->middleware('signed');

    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset');
});

// Auth routes
Route::group(['middleware' => 'auth'], function () {

    // Profile routes
    Route::get('/profile-picture', [ProfileController::class, 'getProfilePicture'])
        ->name('profile.picture');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::post('/save-profile', [ProfileController::class, 'saveProfile'])
        ->name('save.profile');

    // Product routes
    Route::get('/api/products', [ProductController::class, 'getProducts'])
        ->name('api.products');

    Route::resource('/products', ProductController::class);
});
