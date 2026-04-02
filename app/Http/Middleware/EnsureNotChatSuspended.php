<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotChatSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $program = $request->route('program');
        $user = auth()->user();

        if ($program && $user) {
            $enrollment = \App\Models\Enrollment::where('program_id', $program->id)
                ->where('user_id', $user->id)
                ->first();

            if ($enrollment && $enrollment->chat_status === 'suspended') {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'You are suspended from this chat.'], 403);
                }
                return back()->with('error', 'You are suspended from this chat.');
            }
        }

        return $next($request);
    }
}
