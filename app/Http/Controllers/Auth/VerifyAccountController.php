<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerificationQueue;
use App\Models\User;
use App\Traits\ResponseHelper;

class VerifyAccountController extends Controller
{

    use ResponseHelper;

    /**
     * Show verify account page
     *
     * @return View
     */
    public function verifyAccountForm()
    {
        $user = User::where('token', request()->segment(2))
            ->firstOrFail();

        // Check if verification link is expired
        if (!request()->hasValidSignature()) {
            abort(404, 'Invalid Link');
        }

        return view('auth.verify', compact('user'));
    }

    /**
     * Verify user account
     *
     * @param string $token
     * @return ResponseTrait
     */

    public function verifyAccount($token)
    {
        try {
            // Find user by token
            $user = User::where('token', $token)->firstOrFail();

            // Set email verified
            $user->update([
                'is_verified' => true,
            ]);

            // Return success response
            return $this->success([], 'User account has been verified', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('User account cant be found', 404);
        }
    }

    /**
     * Resend verification link page
     *
     * @return View
     */
    public function resendVerificationForm()
    {
        // Check if user is verified
        if (auth()->user()->is_verified) {

            // Redirect to landing page
            return redirect()->route('landing.page');
        }

        // Return view
        return view('auth.resend-verification');
    }

    /**
     * Resend verification link
     *
     * @return ResponseTrait
     */
    public function resendVerification()
    {
        try {
            // Find user by email
            $user = User::where('email', auth()->user()->email)->firstOrFail();

            // Send verification email
            SendEmailVerificationQueue::dispatch($user);

            // Return success response
            return $this->success([], 'Verification link has been sent', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('User account cant be found', 404);
        }
    }
}
