@extends('layouts.dashboard')

@section('title', 'Premium Subscriptions Management')

@section('styles')
    <style>
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            display: inline-block;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: rgb(22, 163, 74);
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-expiring {
            background: rgba(245, 158, 11, 0.1);
            color: rgb(217, 119, 6);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-expired {
            background: rgba(239, 68, 68, 0.1);
            color: rgb(220, 38, 38);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-trial {
            background: rgba(59, 130, 246, 0.1);
            color: rgb(29, 78, 216);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .premium-table {
            border-collapse: collapse;
            width: 100%;
        }

        .premium-table thead {
            background: var(--bg-secondary);
        }

        .premium-table th,
        .premium-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .premium-table tbody tr:hover {
            background: var(--bg-tertiary, rgba(0, 0, 0, 0.02));
        }

        .expiry-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .expiry-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .expiry-soon {
            background: rgba(245, 158, 11, 0.2);
            color: rgb(217, 119, 6);
        }

        .expiry-danger {
            background: rgba(239, 68, 68, 0.2);
            color: rgb(220, 38, 38);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn-primary {
            background: rgba(11, 77, 115, 0.1);
            color: #0B4D73;
        }

        .action-btn-primary:hover {
            background: rgba(11, 77, 115, 0.2);
        }

        .action-btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: rgb(220, 38, 38);
        }

        .action-btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Premium Subscriptions
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Manage and monitor user premium subscriptions.</p>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-secondary glass">
                    <i class="fas fa-download"></i>
                    <span>Export Report</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="analytics-card p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span style="color: var(--text-secondary);">Total Premium Users</span>
                    <i class="fas fa-crown text-yellow-500 text-lg"></i>
                </div>
                <p class="text-2xl font-black">{{ $stats['total'] }}</p>
                <p class="text-xs mt-2" style="color: var(--text-secondary);">Including active & expired</p>
            </div>

            <div class="analytics-card p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span style="color: var(--text-secondary);">Active Subscriptions</span>
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                </div>
                <p class="text-2xl font-black text-green-600">{{ $stats['active'] }}</p>
                <p class="text-xs mt-2" style="color: var(--text-secondary);">Currently active</p>
            </div>

            <div class="analytics-card p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span style="color: var(--text-secondary);">Expiring Soon</span>
                    <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
                </div>
                <p class="text-2xl font-black text-amber-600">{{ $stats['expiring'] }}</p>
                <p class="text-xs mt-2" style="color: var(--text-secondary);">In next 7 days</p>
            </div>

            <div class="analytics-card p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span style="color: var(--text-secondary);">Expired</span>
                    <i class="fas fa-times-circle text-red-500 text-lg"></i>
                </div>
                <p class="text-2xl font-black text-red-600">{{ $stats['expired'] }}</p>
                <p class="text-xs mt-2" style="color: var(--text-secondary);">Need renewal</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass rounded-lg p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Search by Name or Email</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Type name or email..." class="w-full px-4 py-2 rounded-lg border"
                        style="border-color: var(--border-color);">
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Filter by Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-lg border"
                        style="border-color: var(--border-color);">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring Soon
                        </option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Filter by Plan</label>
                    <select name="plan" class="w-full px-4 py-2 rounded-lg border"
                        style="border-color: var(--border-color);">
                        <option value="">All Plans</option>
                        <option value="monthly" {{ request('plan') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="termly" {{ request('plan') === 'termly' ? 'selected' : '' }}>Termly (4 months)
                        </option>
                        <option value="annually" {{ request('plan') === 'annually' ? 'selected' : '' }}>Annually
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply
                    </button>
                    <a href="{{ route('admin.premiums.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Premium Subscriptions Table -->
        <div class="glass rounded-lg overflow-hidden">
            @if ($premiums->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Expires On</th>
                                <th>Days Remaining</th>
                                <th>Auto-Renewal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($premiums as $user)
                                @php
                                    $daysRemaining = $user->premium_ends_at ? now()->diffInDays($user->premium_ends_at, false) : -1;
                                    $statusClass = 'status-active';
                                    $statusText = 'Active';

                                    if ($user->premium_status === 'expired' || $daysRemaining < 0) {
                                        $statusClass = 'status-expired';
                                        $statusText = 'Expired';
                                    } elseif ($daysRemaining <= 7 && $daysRemaining >= 0) {
                                        $statusClass = 'status-expiring';
                                        $statusText = 'Expiring Soon';
                                    } elseif ($user->premium_status === 'trial') {
                                        $statusClass = 'status-trial';
                                        $statusText = 'Trial';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name) }}"
                                                alt="{{ $user->first_name }}" class="w-8 h-8 rounded-full object-cover">
                                            <div>
                                                <p class="font-medium">{{ $user->first_name }} {{ $user->last_name }}
                                                </p>
                                                <p class="text-xs" style="color: var(--text-secondary);">{{ $user->username }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="text-xs font-bold uppercase">
                                            {{ $user->getRoleNames()->first() ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-semibold capitalize">
                                            {{ $user->premium_plan ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->premium_ends_at)
                                            <span class="font-medium">
                                                {{ $user->premium_ends_at->format('M d, Y') }}
                                            </span>
                                            <p class="text-xs" style="color: var(--text-secondary);">
                                                {{ $user->premium_ends_at->format('H:i A') }}
                                            </p>
                                        @else
                                            <span style="color: var(--text-secondary);">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->premium_ends_at)
                                            <div class="expiry-indicator">
                                                @if ($daysRemaining < 0)
                                                    <span class="expiry-badge expiry-danger">
                                                        Expired {{ abs($daysRemaining) }} days ago
                                                    </span>
                                                @elseif ($daysRemaining <= 7)
                                                    <span class="expiry-badge expiry-soon">
                                                        {{ $daysRemaining }} days
                                                    </span>
                                                @else
                                                    <span style="color: var(--text-secondary); font-size: 0.875rem;">
                                                        {{ $daysRemaining }} days
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color: var(--text-secondary);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->auto_renewal_enabled)
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                <i class="fas fa-check-circle"></i> Enabled
                                            </span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                                <i class="fas fa-times-circle"></i> Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="action-btn action-btn-primary" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($user->premium_status === 'active')
                                                <form method="POST"
                                                    action="{{ route('admin.premiums.extend', $user->id) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn action-btn-primary"
                                                        title="Extend Subscription">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('admin.premiums.cancel', $user->id) }}"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to cancel this subscription?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn action-btn-danger"
                                                        title="Cancel Subscription">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @elseif ($user->premium_status === 'expired')
                                                <form method="POST"
                                                    action="{{ route('admin.premiums.reactivate', $user->id) }}"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn action-btn-primary"
                                                        title="Reactivate Subscription">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t" style="border-color: var(--border-color);">
                    {{ $premiums->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-4xl mb-4" style="color: var(--text-secondary);"></i>
                    <p class="text-lg font-medium mb-2">No premium subscriptions found</p>
                    <p style="color: var(--text-secondary);">Try adjusting your filters or search criteria.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
