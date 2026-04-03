<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    /**
     * Clear all application caches
     * 
     * Clears: config cache, view cache, and application cache
     * Access: Only available in local environment or for admins
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        // Check if in local environment or admin user
        if (app()->environment() !== 'local' && !auth()->user()?->hasRole('Admin')) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        try {
            // Clear cache
            Artisan::call('cache:clear');
            $cacheCleared = true;
        } catch (\Exception $e) {
            $cacheCleared = false;
            \Log::error('Cache clear failed: ' . $e->getMessage());
        }

        try {
            // Clear config cache
            Artisan::call('config:clear');
            $configCleared = true;
        } catch (\Exception $e) {
            $configCleared = false;
            \Log::error('Config clear failed: ' . $e->getMessage());
        }

        try {
            // Clear view cache
            Artisan::call('view:clear');
            $viewCleared = true;
        } catch (\Exception $e) {
            $viewCleared = false;
            \Log::error('View clear failed: ' . $e->getMessage());
        }

        // Build response message
        $messages = [];
        if ($cacheCleared) $messages[] = '✓ Application cache cleared';
        if ($configCleared) $messages[] = '✓ Configuration cache cleared';
        if ($viewCleared) $messages[] = '✓ View cache cleared';

        $message = implode(' | ', $messages);

        // Log this action
        \Log::info('[ADMIN] Cache cleared by user: ' . (auth()->user()?->email ?? 'unknown'));

        // Return response based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
