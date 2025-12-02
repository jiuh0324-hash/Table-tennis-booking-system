<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Validator; 

class ProfileController extends Controller
{
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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

    public function updateAvatar(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $user = $request->user();
            $file = $request->file('avatar');

            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('avatars', $fileName, 'public');

            $user->update(['avatar' => $fileName]);

            return redirect()->route('profile.edit')
                ->with('status', 'avatar-updated')
                ->with('success', 'Avatar updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Avatar upload failed: ' . $e->getMessage());
        }
    }

    public function removeAvatar(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $user->update(['avatar' => null]);

            return redirect()->route('profile.edit')
                ->with('status', 'avatar-removed')
                ->with('success', 'Avatar removed successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove avatar: ' . $e->getMessage());
        }
    }
}