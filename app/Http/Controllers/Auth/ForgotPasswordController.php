<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\SendPasswordRequest;
use App\Jobs\SendPasswordResetQueue;
use App\Models\User;
use App\Traits\ResponseHelper;

class ForgotPasswordController extends Controller
{
    use ResponseHelper;

    /**
     * Forgot password page
     *
     * @return View
     */
    public function forgotPasswordForm()
    {
        return view('auth.password-request');
    }

    /**
     * Forgot password process
     *
     * @param SendPasswordRequest $request
     * @return ResponseTrait
     */
    public function forgotPassword(SendPasswordRequest $request)
    {
        try {
            // Find user by email
            $user = User::where('email', $request->email)
                ->firstOrFail();

            // Send reset password email
            dispatch(new SendPasswordResetQueue($user));

            // Return success response
            return $this->success([], 'Reset password link has been sent to your email', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error($e->getMessage(), 404);
        }
    }

    /**
     * Reset password page
     *
     * @param string $token
     *
     * @return View
     */
    public function resetPasswordForm($token)
    {
        $user = User::where('token', $token)
            ->firstOrFail();

        if (!request()->hasValidSignature()) {
            abort(404, 'Invalid Link');
        }

        return view('auth.password-reset', compact('token'));
    }

    /**
     * Reset password process
     *
     * @param ForgotPasswordRequest $request
     * @param string $token
     *
     * @return ResponseTrait
     */
    public function resetPassword(ForgotPasswordRequest $request)
    {
        try {
            // Find user by token
            $user = User::where('token', $request->token)
                ->firstOrFail();

            // Update password
            $user->updateOrFail([
                'password' => $request->password,
            ]);

            // Return success response
            return $this->success([], 'User account has been reset', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('User account cant be found', 404);
        }
    }
}
