<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PushSubscriptionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Push Notification Routes
 */
Route::middleware('auth:sanctum')->prefix('push')->group(function () {
    // Subscribe to push notifications
    Route::post('/subscribe', [PushSubscriptionController::class, 'subscribe']);

    // Unsubscribe from push notifications
    Route::post('/unsubscribe', [PushSubscriptionController::class, 'unsubscribe']);

    // Get user's subscriptions
    Route::get('/subscriptions', [PushSubscriptionController::class, 'getSubscriptions']);

    // Update preferences for a subscription
    Route::put('/subscriptions/{subscription}/preferences', [PushSubscriptionController::class, 'updatePreferences']);

    // Get global user notification preferences
    Route::get('/preferences', [PushSubscriptionController::class, 'getPreferences']);

    // Update global user notification preferences
    Route::put('/preferences', [PushSubscriptionController::class, 'updateGlobalPreferences']);

    // Get VAPID public key
    Route::get('/vapid-public-key', [PushSubscriptionController::class, 'getVapidPublicKey']);

    // Test push notification
    Route::post('/test', [PushSubscriptionController::class, 'test']);

    // Log push notification event
    Route::post('/log', function (Request $request) {
        $validated = $request->validate([
            'action' => 'required|in:notification_displayed,notification_clicked,notification_dismissed',
            'type' => 'required|string',
            'timestamp' => 'required|date_format:Y-m-d\TH:i:sZ',
        ]);

        // Log to database or analytics service
        \Illuminate\Support\Facades\Log::info('[Push Event]', [
            'user_id' => $request->user()->id,
            'action' => $validated['action'],
            'type' => $validated['type'],
            'timestamp' => $validated['timestamp'],
        ]);

        return response()->json(['success' => true]);
    });
});
