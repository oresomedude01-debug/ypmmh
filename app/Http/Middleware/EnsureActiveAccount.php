<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class EnsureActiveAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Already verified — allow through
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // Allow access to verification-related routes and logout
        $allowedRoutes = ['verification.notice', 'verification.verify', 'verification.send', 'logout'];
        if ($request->routeIs(...$allowedRoutes)) {
            return $next($request);
        }

        // Parents and Children (public sign-ups) — STRICT verification required, no grace period
        if ($user->hasRole('Parent') || $user->hasRole('Child')) {
            return redirect()->route('verification.notice')
                ->with('error', 'You must verify your email address before you can access your account. Please check your inbox (and spam folder) for the verification link.');
        }

        // Admin and Mentor (internal accounts) — 3-day grace period
        $createdAt = $user->created_at ?? now();
        $daysSinceCreation = $createdAt->diffInDays(now());

        if ($daysSinceCreation >= 3) {
            session()->flash('account_disabled', true);
            return redirect()->route('verification.notice')
                ->with('error', 'Your account has been temporarily disabled due to lack of verification. Please verify your email immediately to restore access.');
        }

        // Within 3-day grace period for internal accounts — remind them
        if (!$request->expectsJson() && $request->isMethod('get') && !$request->routeIs('verification.*')) {
            session()->now('warning', 'Please verify your email address. Your account will be disabled in ' . (3 - $daysSinceCreation) . ' days if not verified.');
        }

        return $next($request);
    }
}
