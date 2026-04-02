<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Subscribe to push notifications
     * POST /api/push/subscribe
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
            'p256dh' => 'required|string',
            'device_type' => 'required|in:web,mobile,tablet',
            'browser' => 'nullable|string',
            'user_agent' => 'nullable|string',
        ]);

        try {
            // Check if subscription already exists
            $subscription = PushSubscription::where('user_id', $request->user()->id)
                ->where('endpoint', $validated['endpoint'])
                ->first();

            if ($subscription) {
                // Update existing subscription
                $subscription->update([
                    'public_key' => $validated['public_key'],
                    'auth_token' => $validated['auth_token'],
                    'p256dh' => $validated['p256dh'],
                    'device_type' => $validated['device_type'],
                    'browser' => $validated['browser'],
                    'user_agent' => $validated['user_agent'],
                    'ip_address' => $request->ip(),
                    'is_active' => true,
                    'failure_count' => 0,
                ]);
                $isNew = false;
            } else {
                // Create new subscription
                $subscription = PushSubscription::create([
                    'user_id' => $request->user()->id,
                    'endpoint' => $validated['endpoint'],
                    'public_key' => $validated['public_key'],
                    'auth_token' => $validated['auth_token'],
                    'p256dh' => $validated['p256dh'],
                    'device_type' => $validated['device_type'],
                    'browser' => $validated['browser'],
                    'user_agent' => $validated['user_agent'],
                    'ip_address' => $request->ip(),
                ]);
                $isNew = true;
            }

            Log::info('Push subscription ' . ($isNew ? 'created' : 'updated'), [
                'user_id' => $request->user()->id,
                'subscription_id' => $subscription->id,
                'device_type' => $validated['device_type'],
            ]);

            return response()->json([
                'success' => true,
                'message' => $isNew ? 'Subscribed to push notifications' : 'Subscription updated',
                'subscription_id' => $subscription->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error subscribing to push', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to push notifications',
            ], 500);
        }
    }

    /**
     * Unsubscribe from push notifications
     * POST /api/push/unsubscribe
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $deleted = PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint', $validated['endpoint'])
            ->delete();

        if ($deleted) {
            Log::info('Push subscription deleted', [
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Unsubscribed from push notifications',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Subscription not found',
        ], 404);
    }

    /**
     * Get user's subscriptions
     * GET /api/push/subscriptions
     */
    public function getSubscriptions(Request $request)
    {
        $subscriptions = $request->user()->pushSubscriptions()
            ->select('id', 'device_type', 'browser', 'is_active', 'last_used_at', 'created_at')
            ->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
            'count' => $subscriptions->count(),
        ]);
    }

    /**
     * Update notification preferences for a subscription
     * PUT /api/push/subscriptions/{id}/preferences
     */
    public function updatePreferences(Request $request, PushSubscription $subscription)
    {
        // Authorize
        if ($subscription->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'notify_content_updates' => 'boolean',
            'notify_messages' => 'boolean',
            'notify_achievements' => 'boolean',
            'notify_program_updates' => 'boolean',
            'notify_admin_alerts' => 'boolean',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
        ]);

        $subscription->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Get VAPID public key
     * GET /api/push/vapid-public-key
     */
    public function getVapidPublicKey()
    {
        return response()->json([
            'vapidPublicKey' => $this->pushService->getVapidPublicKey(),
        ]);
    }

    /**
     * Get user's notification preferences
     * GET /api/push/preferences
     */
    public function getPreferences(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'push_notifications_enabled' => $user->push_notifications_enabled,
            'email_notifications_enabled' => $user->email_notifications_enabled,
            'notification_preferences' => $user->notification_preferences ?? [],
        ]);
    }

    /**
     * Update global user notification preferences
     * PUT /api/push/preferences
     */
    public function updateGlobalPreferences(Request $request)
    {
        $validated = $request->validate([
            'push_notifications_enabled' => 'boolean',
            'email_notifications_enabled' => 'boolean',
            'notification_preferences' => 'nullable|json',
        ]);

        $request->user()->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated',
        ]);
    }

    /**
     * Test push notification
     * POST /api/push/test
     */
    public function test(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:push_subscriptions,id',
        ]);

        $subscription = PushSubscription::find($validated['subscription_id']);

        // Authorize
        if ($subscription->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $success = $this->pushService->testSend($subscription);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Test notification sent!' : 'Failed to send test notification',
        ]);
    }
}
