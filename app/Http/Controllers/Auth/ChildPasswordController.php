<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChildPasswordController extends Controller
{
    /**
     * Handle automatic verification and login for child accounts
     */
    public function setup(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        // Log the user in
        Auth::login($user);

        return redirect()->route('child.set-password')
            ->with('success', 'Email verified! Please set your new password to complete your account setup.');
    }

    /**
     * Show the set password form
     */
    public function show()
    {
        $user = Auth::user();

        // Only children who have verified their email can set password
        if (!$user || !$user->hasRole('Child') || !$user->hasVerifiedEmail()) {
            return redirect('/login')->with('error', 'Please verify your email first.');
        }

        return view('auth.child-set-password', ['user' => $user]);
    }

    /**
     * Set the child's password
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only children who have verified their email can set password
        if (!$user || !$user->hasRole('Child') || !$user->hasVerifiedEmail()) {
            return redirect('/login')->with('error', 'Please verify your email first.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Passwords do not match',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('child.dashboard')->with('success', 'Password set successfully! Welcome to your dashboard.');
    }
}
