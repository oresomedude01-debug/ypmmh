<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Child needs to set password after verification
            if ($request->user()->hasRole('Child')) {
                return redirect()->route('child.set-password')->with('info', 'Please set your password to complete registration.');
            }
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Child needs to set password after email verification
        if ($request->user()->hasRole('Child')) {
            return redirect()->route('child.set-password')->with('success', 'Email verified! Please set your password.');
        }

        // Flag first-time verified parents to show the welcome/onboarding modal
        if ($request->user()->hasRole('Parent')) {
            session()->put('show_welcome_modal', true);
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
