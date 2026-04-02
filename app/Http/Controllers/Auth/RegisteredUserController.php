<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:Parent,Child',
            'date_of_birth' => 'nullable|required_if:role,Child|date|before:' . now()->subYears(7)->format('Y-m-d'),
            'gender' => 'nullable|string|in:male,female',
        ], [
            'date_of_birth.before' => 'Participants must be at least 7 years old.',
            'date_of_birth.required_if' => 'Date of birth is required for participants.',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Generate unique number for the user
        $prefix = $request->role === 'Parent' ? 'PR' : 'PT';
        $user->unique_number = $prefix . date('Y') . str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $user->save();

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration Email Error: ' . $e->getMessage());
            session()->flash('error', 'Account created successfully, but we encountered an issue sending your verification email. Please try resending it later.');
        }

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
