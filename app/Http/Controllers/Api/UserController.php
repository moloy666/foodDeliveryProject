<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

use App\Models\User;

use App\Services\CloudinaryService;

class UserController extends Controller
{
    use ApiResponse;
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }


    public function listOtherUsers(Request $request)
    {
        $user = $request->user();

        $otherUsers = User::select('uid', 'name', 'status')
            ->where('id', '!=', $user->id)
            ->where('status', 'active')
            ->get();


        return $this->successResponse(
            200,
            'Other users',
            ['otherUsers' => $otherUsers]
        );
    }

    public function saveProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();
        try {
            $imageUrl = $this->cloudinary->upload(
                $request->file('profile_image'),
                'user-profile'
            );

            if (!$imageUrl) {
                return response()->json([
                    'message' => 'Image upload failed'
                ], 500);
            }

            $user->profile_image = $imageUrl['url'];
            $user->save();

            return $this->successResponse(
                200,
                'User profile image save successfully',
                ['profile_image' => $imageUrl['url']]
            );
        } catch (\Exception $e) {

            return $this->errorResponseResponse(
                500,
                'Profile image upload failed',
                ['profile_image_upload_failed']
            );
        }
    }


    public function updateProfileDetails(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'dob'    => 'nullable|date|before:today',
        ]);

        $user = $request->user();

        try {
            $user->fill([
                'name'   => $request->name,
                'gender' => $request->gender,
                'dob'    => $request->dob,
            ]);

            if (!$user->isDirty()) {
                return $this->successResponse(
                    200,
                    'No changes detected',
                    []
                );
            }

            $user->save();
            return $this->successResponse(
                200,
                'User profile details updated successfully',
                [
                    'name'   => $user->name,
                    'gender' => $user->gender,
                    'dob'    => $user->dob,
                ]
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                500,
                'Profile update failed',
                ['profile_update_failed']
            );
        }
    }
}
