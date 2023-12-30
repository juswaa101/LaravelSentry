<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in
        $user = auth()->user();
        if ($user) {
            // Check if 2FA is enabled for the user
            if ($user->is_two_factor_enabled == 1) {
                // Check if the current route requires 2FA
                $requiresTwoFactor = $this->routeRequiresTwoFactor($request);

                // If 2FA is required and not completed, redirect to the 2FA page
                if ($requiresTwoFactor && !$user->is_two_factor_verified) {
                    return redirect()->route('two-factor-authentication.form');
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if the current route requires Two Factor Authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function routeRequiresTwoFactor(Request $request): bool
    {
        // List the protected routes from the configuration file
        $protectedRoutes = config('two_factor.protected_routes', []);

        // Check if the current route requires 2FA
        return in_array($request->route()->getName(), $protectedRoutes);
    }
}
