<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Handle Profile Picture Upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validatedData['profile_picture'] = $path;
        }

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Submit a request for account deletion.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Check if a request already exists
        $exists = \App\Models\Report::where('reporter_id', $user->id)
            ->where('reportable_type', \App\Models\User::class)
            ->where('reportable_id', $user->id)
            ->where('reason', 'like', 'Account Deletion Request%')
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return Redirect::route('profile.edit')->with('status', 'deletion-request-pending');
        }

        $report = \App\Models\Report::create([
            'reporter_id' => $user->id,
            'reportable_id' => $user->id,
            'reportable_type' => \App\Models\User::class,
            'reason' => 'Account Deletion Request: User requested to delete their account.',
            'status' => 'pending',
        ]);

        // Notify Admins
        $admins = \App\Models\User::role('Admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewReportNotification($report));

        return Redirect::route('profile.edit')->with('status', 'deletion-request-sent');
    }
}
