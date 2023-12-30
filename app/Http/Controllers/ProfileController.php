<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\SaveProfileRequest;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
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
        return view('profile.index');
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

                // If there is existing avatar
                if ($user->avatar) {
                    // Update the avatar in storage
                    Storage::disk('public')->put($filepath, $content);
                } else {
                    // Create new avatar in storage
                    Storage::disk('public')->put(
                        $filepath,
                        $content
                    );
                }

                // Update the avatar in database
                $user->updateOrFail(['avatar' => $content]);
            }

            // update password if changed in the request
            if ($request->filled('password') && $user->password != $request->password) {
                $user->updateOrFail(['password' => $request->password]);
            }

            // Return success response
            return $this->success([], 'Profile updated successfully.', 200);
        } catch (\Exception $e) {
            // Return error response
            return $this->error('Something went wrong. Please try again later.', 500);
        }
    }
}
