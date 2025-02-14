<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Update basic info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update profile
        $profile->update([
            'bio' => $request->bio,
            'phone_number' => $request->phone_number,
            'gardening_level' => $request->gardening_level,
            'plant_preferences' => $request->plant_preferences,
        ]);

        // Update location if provided
        if ($request->has('location')) {
            $profile->update([
                'location_data' => [
                    'address' => $request->location['address'],
                    'city' => $request->location['city'],
                    'state' => $request->location['state'],
                    'zip' => $request->location['zip'],
                    'latitude' => $request->location['latitude'],
                    'longitude' => $request->location['longitude'],
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $profile->update([
            'notification_preferences' => $request->preferences
        ]);

        return response()->json(['message' => 'Notification preferences updated']);
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $profile = $user->profile;

        $profile->update([
            'location_data' => $validated
        ]);

        return response()->json(['message' => 'Location updated successfully']);
    }

    public function userProfile()
    {
        return view('user.profile.edit', ['user' => auth()->user()]);
    }

    public function updateUserProfile(Request $request)
    {
        // Add validation and update logic
    }

    public function sellerProfile()
    {
        return view('seller.profile.edit', ['user' => auth()->user()]);
    }

    public function updateSellerProfile(Request $request)
    {
        // Add validation and update logic
    }
}
