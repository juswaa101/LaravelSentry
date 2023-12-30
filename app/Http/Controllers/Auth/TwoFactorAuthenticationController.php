<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyTwoFactorAuthCodeRequest;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;

class TwoFactorAuthenticationController extends Controller
{
    use ResponseHelper;

    /**
     * Show the two factor authentication form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
            $user = User::where('two_factor_codes', 'LIKE', '%' . $request->code . '%')
                ->first();

            // if user not found with the two factor authentication code
            if (!$user) {
                return $this->error('Invalid two factor authentication code.', 404);
            }

            // Login the user
            auth()->login($user);

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
}
