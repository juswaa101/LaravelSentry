<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\BrowserSessionController;
use App\Http\Requests\Profile\SaveProfileRequest;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    use ResponseHelper;

    /**
     * Show the profile page.
     *
     * @return View
     */
    public function index()
    {
        // Create a new session controller instance
        $session = new BrowserSessionController();

        // Get all the browser sessions
        $sessions = $session->getBrowserSessions();

        return view('profile.index', compact('sessions'));
    }

    /**
     * Get the profile picture.
     *
     * @return JsonResponse
     */
    public function getProfilePicture()
    {
        $user = User::findOrFail(auth()->user()->id);
        return response()->json(['data' => $user], 200);
    }

    /**
     * Save the profile.
     *
     * @param SaveProfileRequest $request
     * @return JsonResponse
     */
    public function saveProfile(SaveProfileRequest $request)
    {
        try {
            // Find and get the user
            $user = User::findOrFail(auth()->user()->id);
            $filepath = 'profile/'  . $user->id . '.png';
            $content = null;

            // Update avatar if changed in the request
            if ($request->has('profile') && $user->avatar != $request->profile) {
                $content = base64_encode(file_get_contents($request->profile));

                Storage::disk('public')->put($filepath, $content);

                // Update the avatar in database
                $user->updateOrFail(['avatar' => $content]);
            }

            // update password if changed in the request
            if ($request->filled('password') && $user->password != $request->password) {
                $user->updateOrFail(['password' => $request->password]);
            }

            // update both password and profile exists in the request
            if ($request->filled('password') && $request->has('profile')) {
                $content = base64_encode(file_get_contents($request->profile));

                Storage::disk('public')->put($filepath, $content);

                $user->updateOrFail([
                    'password' => $request->password,
                    'avatar' => $content
                ]);
            }

            // Return success response
            return $this->success([], 'Profile updated successfully.', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('Something went wrong. Please try again later.', 500);
        }
    }
}
