@extends('layouts.dashboard')

@section('title', 'Community Hub')

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-[#0B4D73] to-blue-600 bg-clip-text text-transparent">
                    Community Hub
                </h1>
                <p class="text-slate-600">Access and moderate your program communities.</p>
            </div>

            @if(auth()->user()->hasRole('Admin'))
                <form action="{{ route('admin.communities.index') }}" method="GET" class="relative w-full md:w-72">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all shadow-sm"
                        placeholder="Filter communities...">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </form>
            @endif
        </div>

        <!-- Push Notification Permission Banner -->
        <div id="push-permission-banner" class="hidden glass rounded-2xl overflow-hidden border border-blue-100 shadow-sm bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="px-6 py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">Enable Desktop Notifications</h4>
                        <p class="text-sm text-slate-600">Get instantly notified when new messages arrive in your community chats.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto shrink-0">
                    <button onclick="dismissPushBanner()" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 bg-white/50 hover:bg-white rounded-xl transition-colors flex-1 md:flex-none">
                        Later
                    </button>
                    <button onclick="requestPushPermission()" class="px-5 py-2 text-sm font-bold text-white bg-[#0B4D73] hover:bg-[#093e5d] rounded-xl transition-colors shadow-sm flex-1 md:flex-none">
                        Enable Notifications
                    </button>
                </div>
            </div>
        </div>

        <!-- Active Communities List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($programs as $program)
                <div
                    class="glass rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 text-[#0B4D73] flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                                <i class="fas fa-comments"></i>
                            </div>
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-full">
                                {{ $program->children_count }} Members
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 mb-2 truncate">{{ $program->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6">Join the conversation with students and mentors in
                            this specialized hub.</p>

                        @php
                            $rolePrefix = auth()->user()->hasRole('Admin') ? 'admin' : 'mentor';
                        @endphp
                        <a href="{{ route($rolePrefix . '.communities.show', $program->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/10">
                            <span>Open Chatroom</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass rounded-3xl p-16 text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users-slash text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">No communities found</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">You are not currently assigned to any program communities.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('push-permission-banner');
        
        // ── Push Subscription Helpers ─────────────────────────────────────────
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Only show if the browser supports notifications and we haven't asked yet or been denied
        if ('Notification' in window && 'serviceWorker' in navigator) {
            const hasDismissed = localStorage.getItem('push_banner_dismissed') === 'true';
            
            if (Notification.permission === 'default' && !hasDismissed) {
                banner.classList.remove('hidden');
            }
        }
        
        window.dismissPushBanner = function() {
            localStorage.setItem('push_banner_dismissed', 'true');
            banner.classList.add('opacity-0', '-translate-y-4');
            setTimeout(() => banner.classList.add('hidden'), 300);
        };
        
        window.requestPushPermission = async function() {
            try {
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    // Register subscription with server
                    await registerPushSubscription();
                    
                    if (typeof showToast === 'function') {
                        showToast('Notifications enabled successfully!', 'success');
                    }
                    banner.classList.add('hidden');
                } else {
                    window.dismissPushBanner();
                }
            } catch (error) {
                console.error("Error requesting permission:", error);
                window.dismissPushBanner();
            }
        };

        async function registerPushSubscription() {
            try {
                const registration = await navigator.serviceWorker.ready;
                
                // Get VAPID public key from server
                const keyResponse = await fetch('/api/push/vapid-public-key');
                const { vapidPublicKey } = await keyResponse.json();
                
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
                });

                const p256dh = btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh'))));
                const auth = btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth'))));

                await fetch('/api/push/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        public_key: p256dh,
                        auth_token: auth,
                        p256dh: p256dh, // redundant but expected by some schemas
                        device_type: 'web',
                        browser: navigator.userAgent,
                        user_agent: navigator.userAgent
                    })
                });
            } catch (error) {
                console.error('Push registration failed:', error);
                throw error;
            }
        }
    });
</script>
@endsection