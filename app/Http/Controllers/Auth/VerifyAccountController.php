<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
}
