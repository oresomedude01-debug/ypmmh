<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Prevent redirect loops
            if (!$request->is('password/change') && !$request->is('child/set-password') && !$request->is('logout')) {
                if (auth()->user()->hasRole('Child')) {
                    return redirect()->route('child.set-password');
                }
                return redirect()->route('password.change.notice');
            }
        }


        return $next($request);
    }
}
