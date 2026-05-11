<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    /**
     * Display a listing of premium users
     */
    public function index(Request $request)
    {
        $query = User::whereNotNull('premium_status')
            ->where('premium_status', '!=', 'none')
            ->with('roles');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'expiring') {
                // Expiring within 7 days
                $query->where('premium_status', 'active')
                    ->whereBetween('premium_ends_at', [now(), now()->addDays(7)]);
            } elseif ($status === 'expired') {
                $query->where(function ($q) {
                    $q->where('premium_status', 'expired')
                        ->orWhere(function ($q2) {
                            $q2->where('premium_status', 'active')
                                ->where('premium_ends_at', '<', now());
                        });
                });
            } else {
                $query->where('premium_status', $status);
            }
        }

        // Filter by plan
        if ($request->filled('plan')) {
            $query->where('premium_plan', $request->input('plan'));
        }

        $premiums = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => User::where('premium_status', '!=', 'none')->count(),
            'active' => User::where('premium_status', 'active')
                ->where('premium_ends_at', '>', now())
                ->count(),
            'expiring' => User::where('premium_status', 'active')
                ->whereBetween('premium_ends_at', [now(), now()->addDays(7)])
                ->count(),
            'expired' => User::where(function ($q) {
                $q->where('premium_status', 'expired')
                    ->orWhere(function ($q2) {
                        $q2->where('premium_status', 'active')
                            ->where('premium_ends_at', '<', now());
                    });
            })->count(),
        ];

        return view('Admin.Premiums.index', compact('premiums', 'stats'));
    }

    /**
     * Extend a user's premium subscription by one month
     */
    public function extend(User $user)
    {
        if ($user->premium_status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be extended.');
        }

        $user->premium_ends_at = $user->premium_ends_at->addMonth();
        $user->save();

        return back()->with('success', "Premium subscription extended for {$user->first_name} {$user->last_name}");
    }

    /**
     * Cancel a user's premium subscription
     */
    public function cancel(User $user)
    {
        if ($user->premium_status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be cancelled.');
        }

        $user->premium_status = 'expired';
        $user->auto_renewal_enabled = false;
        $user->save();

        return back()->with('success', "Premium subscription cancelled for {$user->first_name} {$user->last_name}");
    }

    /**
     * Reactivate an expired subscription
     */
    public function reactivate(User $user)
    {
        if ($user->premium_status === 'expired') {
            $user->premium_status = 'active';
            $user->premium_ends_at = now()->addMonth();
            $user->save();

            return back()->with('success', "Premium subscription reactivated for {$user->first_name} {$user->last_name}");
        }

        return back()->with('error', 'Only expired subscriptions can be reactivated.');
    }
}
