<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\SendEmailVerificationQueue;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    use ResponseHelper;

    /**
     * Show login page
     *
     * @return View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show register page
     *
     * @return View
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Login process
     *
     * @param LoginRequest $request
     * @return ResponseTrait
     */
    public function loginProcess(LoginRequest $request)
    {
        // Check if user credentials is valid
        if (auth()->attempt($request->validated())) {

            // Check if user account is not verified
            if (auth()->user()->is_verified == null || auth()->user()->is_verified == false) {
                auth()->logout();

                return $this->error('User account is not verified', 401);
            }

            // Reset rate limit after success login
            RateLimiter::clear($request->email . '|' . $request->ip());

            // Return success response
            return $this->success([], 'Login success', 200);
        }

        //If not valid, return error response
        return $this->error('Login failed, please check your credentials', 401);
    }

    /**
     * Register process
     *
     * @param RegisterRequest $request
     * @return ResponseTrait
     */
    public function registerProcess(RegisterRequest $request)
    {
        try {
            // Create user instance
            $user = new User($request->validated());

            // Set remember token
            $user->token = Str::random(10);

            // Save user
            $user->saveOrFail();

            // Send email verification
            dispatch(new SendEmailVerificationQueue($user));

            // Return success response
            return $this->success([], 'User account has been registered', 201);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('User account cant be registered', 500);
        }
    }

    /**
     * Logout process
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Logout user
        auth()->logout();

        // Return success response
        return $this->success([], 'Logout success', 200);
    }
}
