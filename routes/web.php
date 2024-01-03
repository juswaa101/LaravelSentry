<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\BrowserSessionController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
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
})
    ->name('landing.page')
    ->middleware('2fa', 'account.verified');

// Verify account routes
Route::get('/verify/{token}', [VerifyAccountController::class, 'verifyAccountForm'])
    ->name('verify.form')
    ->middleware('signed');

Route::post('/verify/{token}/send', [VerifyAccountController::class, 'verifyAccount'])
    ->name('verify');

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


    // Forgot password routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password/submit', [ForgotPasswordController::class, 'forgotPassword'])
        ->name('password.email')
        ->middleware('throttle:forgot-password-attempt');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetPasswordForm'])
        ->name('password.reset.form')
        ->middleware('signed');

    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset');
});

// Auth routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/resent-verification', [VerifyAccountController::class, 'resendVerificationForm'])
        ->name('verify.account.form');

    Route::post('/resent-verification', [VerifyAccountController::class, 'resendVerification'])
        ->name('resend.verification')
        ->middleware('throttle:verify-account-attempt');

    // Protected routes by Account Verification
    Route::group(['middleware' => 'account.verified'], function () {
        // Two Factor Authentication routes
        Route::get('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'twoFactorAuthForm'])
            ->name('two-factor-authentication.form');

        Route::post('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'verifyTwoFactorAuthCode'])
            ->name('two-factor-authentication.verify');

        Route::post('/two-factor-authentication/send', [TwoFactorAuthenticationController::class, 'sendTwoFactorAuthCode'])
            ->name('two-factor-authentication.send')
            ->middleware('throttle:send-two-factor-auth-attempt');

        // Logout other browser sessions
        Route::post('/logout-other-browser-sessions', [BrowserSessionController::class, 'logoutOtherBrowserSessions'])
            ->name('logout.other.browser.sessions');

        // Logout browser session
        Route::post('/logout-browser-session', [BrowserSessionController::class, 'logoutBrowserSession'])
            ->name('logout.browser.session');
    });

    // Protected routes by Two Factor Authentication and Account Verification
    Route::group(['middleware' => ['2fa', 'account.verified']], function () {
        // Profile routes
        Route::get('/profile-picture', [ProfileController::class, 'getProfilePicture'])
            ->name('profile.picture');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::post('/save-profile', [ProfileController::class, 'saveProfile'])
            ->name('save.profile');

        // Two factor authentication routes
        Route::get('/profile/two-factor-auth-status', [TwoFactorAuthenticationController::class, 'getTwoFactorAuthStatus'])
            ->name('profile.two-factor-auth-status');

        Route::post('/profile/two-factor-auth-status', [TwoFactorAuthenticationController::class, 'twoFactorAuth'])
            ->name('profile.two-factor-auth');

        // Product routes
        Route::get('/api/products', [ProductController::class, 'getProducts'])
            ->name('api.products');

        Route::resource('/products', ProductController::class);
    });
});
