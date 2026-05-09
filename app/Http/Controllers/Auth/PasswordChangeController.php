<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function showChangeForm()
    {
        // If they don't need to change it, redirect to dashboard
        if (!auth()->user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.force-password-change');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Your password has been successfully updated.');
    }
}
