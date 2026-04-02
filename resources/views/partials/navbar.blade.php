<nav id="dashboard-navbar" class="sticky top-0 z-50 px-2 py-2 md:px-6 md:py-4 backdrop-blur-md shadow-sm transition-all opacity-100"
    style="background-color: var(--glass-bg); border-bottom: 1px solid var(--border-color);" role="navigation"
    aria-label="Top navigation">
    <div class="flex items-center justify-between">
        <!-- Left Section: Sidebar Toggle & Search -->
        <div class="flex items-center gap-4 flex-1">
            <!-- Sidebar Toggle Button (Hidden on Mobile) -->
            <button onclick="toggleSidebar()" class="p-2 rounded-lg glass-hover hidden md:block"
                aria-label="Toggle sidebar" aria-expanded="true" aria-controls="sidebar">
                <i class="fas fa-bars text-xl" style="color: var(--text-primary);"></i>
            </button>

            <!-- Search Bar (Visible everywhere) -->
            <div class="relative max-w-full w-full md:max-w-md">
                <input type="search" placeholder="Search..."
                    class="pl-10 pr-4 py-2 w-full rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                    style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                    aria-label="Search" id="globalSearch">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2"
                    style="color: var(--text-secondary);"></i>
            </div>
        </div>

        <!-- Right Section: Actions & User -->
        <div class="flex items-center gap-3 pl-3">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" id="themeToggle" class="p-3 rounded-lg glass-hover shrink-0"
                aria-label="Toggle theme">
                <i class="fas fa-moon text-lg" style="color: var(--text-primary);"></i>
            </button>

            <!-- Notifications Dropdown (Hidden on Mobile) -->
            @php 
                $unreadCount = auth()->user()->unreadNotifications->count(); 
                $notifRoute = 'admin.notifications.index';
                if(auth()->user()->hasRole('Child')) $notifRoute = 'child.notifications.index';
                elseif(auth()->user()->hasRole('Mentor')) $notifRoute = 'mentor.notifications.index';
                elseif(auth()->user()->hasRole('Parent')) $notifRoute = 'parent.notifications';
            @endphp
            <div class="relative dropdown hidden md:block">
                <button onclick="toggleDropdown('notificationsDropdown')" class="p-3 rounded-lg glass-hover relative"
                    aria-label="Notifications" aria-expanded="false" aria-haspopup="true">
                    <i class="fas fa-bell text-lg" style="color: var(--text-primary);"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    @endif
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notificationsDropdown"
                    class="dropdown-content absolute right-0 mt-2 w-80 glass rounded-2xl shadow-xl overflow-hidden"
                    style="background-color: var(--glass-bg); border-color: var(--border-color);" role="menu">
                    <div class="p-4 border-b"
                        style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold" style="color: var(--text-primary);">Notifications</h3>
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $unreadCount > 0 ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500' }}">{{ $unreadCount }}
                                New</span>
                        </div>
                    </div>

                    <div class="max-h-96 overflow-y-auto" style="background-color: var(--glass-bg);">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <a href="{{ route($notifRoute) }}"
                                class="block p-4 border-b glass-hover transition-colors"
                                style="border-color: var(--border-color);" role="menuitem">
                                <div class="flex gap-3">
                                    @php
                                        $iconColor = '#3b82f6';
                                        $bgColor = 'rgba(59, 130, 246, 0.1)';
                                        $icon = 'fa-bell';

                                        if (($notification->data['type'] ?? '') === 'birthday') {
                                            $iconColor = '#ec4899';
                                            $bgColor = 'rgba(236, 72, 153, 0.1)';
                                            $icon = 'fa-birthday-cake';
                                        }
                                    @endphp
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                        style="background-color: {{ $bgColor }}; border: 1px solid rgba(0,0,0,0.05);">
                                        <i class="fas {{ $icon }}" style="color: {{ $iconColor }};"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm leading-tight truncate"
                                            style="color: var(--text-primary);">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </p>
                                        <p class="text-[10px] uppercase font-black mt-1"
                                            style="color: var(--text-secondary);">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center opacity-30">
                                <i class="fas fa-check-circle text-3xl mb-2" style="color: var(--text-primary);"></i>
                                <p class="text-xs uppercase font-black" style="color: var(--text-primary);">All caught up!
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-3 border-t text-center"
                        style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                        <a href="{{ route($notifRoute) }}"
                            class="text-xs font-black uppercase tracking-widest hover:underline"
                            style="color: var(--primary-500);">
                            View All Notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative dropdown">
                <button onclick="toggleDropdown('userDropdown')"
                    class="flex items-center gap-3 p-2 rounded-xl glass-hover" aria-label="User profile"
                    aria-expanded="false" aria-haspopup="true">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt=""
                            class="w-10 h-10 rounded-full object-cover border-2 shadow-sm"
                            style="border-color: var(--primary-500);">
                    @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-sm border-2"
                            style="background-color: var(--bg-primary); color: var(--primary-500); border-color: var(--primary-500);">
                            {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                        </div>
                    @endif
                    <div class="text-left hidden lg:block">
                        <p class="text-sm font-black leading-none" style="color: var(--text-primary);">
                            {{ auth()->user()->first_name }}</p>
                        <p class="text-[10px] uppercase font-black mt-1" style="color: var(--text-secondary);">
                            {{ auth()->user()->roles->first()->name ?? 'Guest' }}
                        </p>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] hidden md:block"
                        style="color: var(--text-secondary);"></i>
                </button>

                <!-- Profile Dropdown Content -->
                <div id="userDropdown"
                    class="dropdown-content absolute right-0 mt-2 w-56 glass rounded-2xl shadow-xl overflow-hidden border"
                    style="background-color: var(--glass-bg); border-color: var(--border-color);" role="menu">
                    <div class="p-4 border-b md:hidden"
                        style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                        <p class="font-black" style="color: var(--text-primary);">{{ auth()->user()->first_name }}
                            {{ auth()->user()->last_name }}</p>
                        <p class="text-xs font-medium" style="color: var(--text-secondary);">{{ auth()->user()->email }}
                        </p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold glass-hover transition-colors"
                            style="color: var(--text-primary);" role="menuitem">
                            <i class="fas fa-user-circle w-5" style="color: var(--text-secondary);"></i>
                            My Profile
                        </a>
                        <a href="{{ route('coming-soon') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold glass-hover transition-colors"
                            style="color: var(--text-primary);" role="menuitem">
                            <i class="fas fa-cog w-5" style="color: var(--text-secondary);"></i>
                            Account Settings
                        </a>
                        <div class="border-t mx-4 my-2" style="border-color: var(--border-color);"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-bold w-full text-left text-red-500 glass-hover transition-colors"
                                role="menuitem">
                                <i class="fas fa-sign-out-alt w-5 transition-transform group-hover:translate-x-1"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>