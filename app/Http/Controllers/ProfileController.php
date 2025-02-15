<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Validate the request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gardening_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced'],
            'plant_preferences' => ['nullable', 'array'],
            'location' => ['nullable', 'array'],
            'notification_preferences' => ['nullable', 'array'],
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Get or create profile
        $profile = $user->profile()->firstOrCreate([]);

        // Update profile
        $profile->update([
            'gardening_level' => $validated['gardening_level'] ?? null,
            'plant_preferences' => $validated['plant_preferences'] ?? [],
            'location_data' => $validated['location'] ?? [],
            'notification_preferences' => $validated['notification_preferences'] ?? [],
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
