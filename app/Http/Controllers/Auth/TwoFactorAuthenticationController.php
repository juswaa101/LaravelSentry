<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyTwoFactorAuthCodeRequest;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TwoFactorAuthenticationController extends Controller
{
    use ResponseHelper;

    /**
     * Show the two factor authentication form.
     *
     * @return View
     */
    public function twoFactorAuthForm()
    {
        return view('auth.two-factor-authentication');
    }

    /**
     * Verify the two factor authentication code to login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyTwoFactorAuthCode(VerifyTwoFactorAuthCodeRequest $request)
    {
        try {
            // Find and get the user with the two factor authentication code
            $user = User::where('is_two_factor_enabled', 1)
                ->where('id', auth()->user()->id)
                ->where('two_factor_codes', 'LIKE', '%' . $request->code . '%')
                ->first();

            // if user not found with the two factor authentication code
            if (!$user) {
                return $this->error('Invalid two factor authentication code.', 404);
            }

            // Update the two factor authentication status
            $user->updateOrFail([
                'is_two_factor_verified' => 1,
            ]);

            // Login the user
            auth()->login($user);

            // Clear the limiter
            RateLimiter::clear(auth()->user()->email . '|' . request()->ip());

            // Return success response
            return $this->success([], 'Two factor authentication code verified successfully.', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('Something went wrong!', 500);
        }
    }

    /**
     * Fetch the two factor authentication status.
     *
     * @return JsonResponse
     */
    public function getTwoFactorAuthStatus()
    {
        try {
            // Find and get the user
            $user = User::findOrFail(auth()->user()->id);

            // Return success response
            return $this->success($user, 'Two factor authentication status fetched successfully.', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Update the two factor authentication status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function twoFactorAuth(Request $request)
    {
        try {
            // Find and get the user
            $user = User::findOrFail(auth()->user()->id);

            // Update the two factor authentication status
            $user->updateOrFail([
                'is_two_factor_verified' => $request->status == 'true' ? "1" : "0",
                'is_two_factor_enabled' => $request->status == 'true' ? "1" : "0",
                'two_factor_codes' => json_encode($request->two_factor_codes)
            ]);

            // Return success response
            return $this->success([], 'Two factor authentication status updated successfully.', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Send the two factor authentication code.
     *
     * @return JsonResponse
     */
    public function sendTwoFactorAuthCode()
    {
        try {
            // Find and get the user
            $user = User::findOrFail(auth()->user()->id);

            // Send the two factor authentication code
            $user->sendTwoFactorAuthenticationCode();

            // Return success response
            return $this->success(
                [],
                'Two factor authentication code was sent successfully to your email. Please check your inbox',
                200
            );
        } catch (\Exception $e) {
            // Return error response
            return $this->error('Something went wrong!', 500);
        }
    }
}
