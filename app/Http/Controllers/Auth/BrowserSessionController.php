<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrowserSessionController extends Controller
{
    use ResponseHelper;

    /**
     * Get all sessions for the current user.
     *
     * @return JsonResponse
     */
    public function getBrowserSessions()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->user()->id)
            ->get();

        return $sessions;
    }

    /**
     * Logout the given session for the current user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logoutBrowserSession(Request $request)
    {
        DB::table('sessions')
            ->where('id', $request->session_id)
            ->where('user_id', auth()->user()->id)
            ->delete();

        // Initialize is current user to false
        $isCurrentUser = false;

        // If the current session is the one being deleted, logout the user
        if ($request->session_id === $request->session()->getId()) {
            $isCurrentUser = true;
            auth()->logout();
        }

        return $this->success(
            [
                'isCurrentUser' => $isCurrentUser
            ],
            'Browser session successfully logged out.',
            200
        );
    }

    /**
     * Logout all other browser sessions for the current user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logoutOtherBrowserSessions(Request $request)
    {
        // Delete all other browser sessions except the current one
        DB::table('sessions')
            ->where('user_id', auth()->user()->id)
            ->where('id', '!=', $request->session_id)
            ->delete();

        // If the current session is the one being deleted, logout the user
        if ($request->session_id === $request->session()->getId()) {
            auth()->logout();
        }

        // Redirect to login page
        return $this->success([], 'Browser sessions successfully logged out.', 200);
    }
}
